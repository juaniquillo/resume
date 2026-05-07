<?php

use App\Jobs\ProcessResumeExport;
use App\Models\Basic;
use App\Models\ResumeExport;
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
