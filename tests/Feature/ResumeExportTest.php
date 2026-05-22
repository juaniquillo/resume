<?php

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Jobs\ProcessResumeExport;
use App\Models\Basic;
use App\Models\Interest;
use App\Models\Reference;
use App\Models\ResumeExport;
use App\Models\Skill;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('it renders the resume export index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.resume.export'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.resume.export')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

test('it can initiate a resume export', function () {
    Queue::fake();

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.resume.export.store'), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('resume_exports', [
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    Queue::assertPushed(ProcessResumeExport::class);
});

test('the background job generates a valid json file', function () {
    Storage::fake('local');

    // Seed some data
    $basic = Basic::factory()->create(['user_id' => $this->user->id]);
    Work::factory()->create(['user_id' => $this->user->id, 'name' => 'Tech Corp']); // Use 'name' which is what WorksCrud uses

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $job = new ProcessResumeExport($export);
    $job->handle();

    $export->refresh();
    if ($export->status === 'failed') {
        dump($export->error);
    }
    expect($export->status)->toBe('completed');
    expect($export->file_path)->not->toBeNull();

    Storage::disk('local')->assertExists($export->file_path);

    $json = Storage::disk('local')->get($export->file_path);
    $data = json_decode($json, true);

    expect($data)->toHaveKey('basics');
    expect($data['basics']['name'])->toBe($basic->name);
    expect($data)->toHaveKey('work');
    expect(count($data['work']))->toBe(1);

    // Test references
    $reference = Reference::factory()->create(['user_id' => $this->user->id]);
    $job->handle();
    $json = Storage::disk('local')->get($export->file_path);
    $data = json_decode($json, true);
    expect($data)->toHaveKey('references');
    expect(count($data['references']))->toBe(1);
    expect($data['references'][0])->toHaveKey('reference');
    expect($data['references'][0]['reference'])->not->toBeEmpty();
    expect($data['references'][0]['reference'])->toBe($reference->reference);

    // Test skills
    $skill = Skill::factory()->create(['user_id' => $this->user->id, 'keywords' => 'PHP, Laravel, Vue']);
    $job->handle();
    $json = Storage::disk('local')->get($export->file_path);
    $data = json_decode($json, true);
    expect($data)->toHaveKey('skills');
    expect($data['skills'][0]['keywords'])->toBe(['PHP', 'Laravel', 'Vue']);

    // Test interests
    $interest = Interest::factory()->create(['user_id' => $this->user->id, 'keywords' => 'Coding, Chess']);
    $job->handle();
    $json = Storage::disk('local')->get($export->file_path);
    $data = json_decode($json, true);
    expect($data)->toHaveKey('interests');
    expect($data['interests'][0]['keywords'])->toBe(['Coding', 'Chess']);
});

test('user can delete their resume export', function () {
    $this->withoutMiddleware();
    Storage::fake('local');
    $user = User::factory()->create();
    $filePath = 'exports/resumes/test.json';
    Storage::disk('local')->put($filePath, 'content');

    $export = ResumeExport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.resume.export.destroy', $export->id));

    $response->assertRedirect();
    $this->assertDatabaseMissing('resume_exports', ['id' => $export->id]);
    Storage::disk('local')->assertMissing($filePath);
});

test('user cannot delete another users resume export', function () {
    $this->withoutMiddleware();
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $export = ResumeExport::create([
        'user_id' => $otherUser->id,
        'file_path' => 'path/to/file.json',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.resume.export.destroy', $export->id));

    $response->assertStatus(404);
    $this->assertDatabaseHas('resume_exports', ['id' => $export->id]);
});

test('user cannot have more than 5 resume exports', function () {
    $this->withoutMiddleware();
    $user = User::factory()->create();
    ResumeExport::factory()->count(5)->create(['user_id' => $user->id]);

    // Create basics so it doesn't fail on missing basics
    Basic::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('dashboard.resume.export.store'));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You can only have up to 5 resume exports. Please delete an old one first.');
    $this->assertDatabaseCount('resume_exports', 5);
});

test('it can download a completed export', function () {
    Storage::fake('local');
    $filePath = 'exports/resumes/test-export.json';
    Storage::disk('local')->put($filePath, json_encode(['test' => 'data']));

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => 'completed',
        'file_path' => $filePath,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.resume.export.download', $export->uuid))
        ->assertSuccessful();
});

test('it cannot download another users export', function () {
    $otherUser = User::factory()->create();
    $export = ResumeExport::create([
        'user_id' => $otherUser->id,
        'status' => 'completed',
        'file_path' => 'some/path.json',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.resume.export.download', $export->uuid))
        ->assertNotFound();
});

test('the background job handles cases with no data correctly', function () {
    Storage::fake('local');

    // User exists but has NO basics, work, etc.
    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $job = new ProcessResumeExport($export);
    $job->handle();

    $export->refresh();
    expect($export->status)->toBe('failed');
    expect($export->file_path)->toBeNull();
    expect($export->error)->toBe(BasicsCrud::MISSING_BASICS_ERROR);
});
