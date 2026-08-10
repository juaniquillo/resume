<?php

use App\Enums\ProcessStatus;
use App\Jobs\ProcessResumeImport;
use App\Livewire\Resume\Import\CreateResumeImport;
use App\Livewire\Resume\Import\DeleteResumeImport;
use App\Livewire\Resume\Import\EditResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

pest()->group('slow');

test('authenticated user can access resume import page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard.resume.import'));

    $response->assertStatus(200);
});

test('user can upload a resume json file', function () {
    Queue::fake();
    Storage::fake('local');

    $user = User::factory()->create();
    $validData = [
        'basics' => [
            'name' => 'John Doe',
            'label' => 'Programmer',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'website' => 'https://johndoe.com',
            'summary' => 'Summary',
            'location' => [
                'address' => 'Street',
                'postalCode' => '12345',
                'city' => 'City',
                'countryCode' => 'US',
                'region' => 'Region',
            ],
            'profiles' => [],
        ],
    ];
    $file = UploadedFile::fake()->createWithContent('resume.json', json_encode($validData));

    Livewire::actingAs($user)
        ->test(CreateResumeImport::class)
        ->set('resumeImport.resume_file', $file)
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('resume_imports', [
        'user_id' => $user->id,
        'file_name' => 'resume.json',
        'status' => ProcessStatus::PENDING,
    ]);

    $import = ResumeImport::first();
    Storage::disk('local')->assertExists($import->file_path);

    Queue::assertPushed(ProcessResumeImport::class, function ($job) use ($import) {
        return $job->import->id === $import->id;
    });
});

test('process resume import job correctly imports data', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $resumeData = [
        'basics' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'label' => 'Software Engineer',
            'location' => [
                'city' => 'New York',
                'country_code' => 'US',
                'address' => '123 Main St',
                'postal_code' => '12345',
            ],
            'profiles' => [
                ['network' => 'twitter', 'username' => 'johndoe', 'url' => 'https://twitter.com/johndoe'],
            ],
        ],
        'work' => [
            [
                'company' => 'Tech Corp',
                'position' => 'Developer',
                'startDate' => '2020-01-01',
                'summary' => 'Doing stuff',
                'highlights' => ['Wrote code', 'Fixed bugs'],
            ],
        ],
        'education' => [
            [
                'institution' => 'University of Life',
                'area' => 'Software Engineering',
                'studyType' => 'bachelor_degree',
                'startDate' => '2016-09-01',
                'endDate' => '2020-06-01',
            ],
        ],
        'skills' => [
            [
                'name' => 'PHP',
                'level' => 'advanced',
                'keywords' => ['Laravel', 'Pest'],
            ],
        ],
    ];

    $filePath = 'imports/resumes/sample.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'sample.json',
        'status' => ProcessStatus::PENDING,
    ]);

    (new ProcessResumeImport($import))->handle();

    $import->refresh();

    $this->assertEquals(ProcessStatus::COMPLETED, $import->status, "Import job failed: {$import->error}");

    $user = $user->refresh();

    $this->assertDatabaseHas('resume_imports', [
        'id' => $import->id,
        'status' => ProcessStatus::COMPLETED,
    ]);

    $basics = $user->basics()->first();
    $location = $basics->location()->first();

    // basics
    $this->assertEquals('John Doe', $basics->name);
    $this->assertEquals('john@example.com', $basics->email);
    $this->assertEquals('New York', $location->city);

    $this->assertEquals('US', $location->country_code);
    $this->assertEquals('123 Main St', $location->address);

    // Works
    $works = $user->works();
    $this->assertEquals(1, $works->count());

    $work = $works->first();
    $highlights = $work->highlights;

    $this->assertEquals('Tech Corp', $work->name);
    $this->assertEquals('Developer', $work->position);
    $this->assertEquals('Doing stuff', $work->summary);
    $this->assertEquals(2, $highlights->count());
    $this->assertEquals('Wrote code', $highlights->first()->highlight);
    $this->assertEquals('Fixed bugs', $highlights->last()->highlight);

    // Education
    $education = $user->education()->first();

    $this->assertEquals('University of Life', $education->institution);
    $this->assertEquals('Software Engineering', $education->area);
    $this->assertEquals('bachelor_degree', $education->study_type);

    // Skills
    $skills = $user->skills();
    $this->assertEquals(1, $skills->count());
    $skill = $skills->first();
    $keywords = $skill->keywords;

    $this->assertEquals('PHP', $skill->name);
    $this->assertEquals('advanced', $skill->level);
    $this->assertEquals(['Laravel', 'Pest'], $keywords);

});

test('user can delete their resume import', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $filePath = 'imports/resumes/test.json';
    Storage::disk('local')->put($filePath, 'content');

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'test.json',
        'status' => ProcessStatus::COMPLETED,
    ]);

    Livewire::actingAs($user)
        ->test(DeleteResumeImport::class, ['resumeImportId' => $import->id])
        ->call('deleteImport')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('resume_imports', ['id' => $import->id]);
    Storage::disk('local')->assertMissing($filePath);
});

test('user cannot delete another users resume import', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $import = ResumeImport::create([
        'user_id' => $otherUser->id,
        'file_path' => 'path/to/file.json',
        'file_name' => 'file.json',
        'status' => ProcessStatus::COMPLETED,
    ]);

    expect(fn () => Livewire::actingAs($user)
        ->test(DeleteResumeImport::class, ['resumeImportId' => $import->id])
        ->call('deleteImport'))->toThrow(ModelNotFoundException::class);

    $this->assertDatabaseHas('resume_imports', ['id' => $import->id]);
});

test('user cannot have more than 5 resume imports', function () {
    $user = User::factory()->create();
    ResumeImport::factory()->count(5)->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->create('new_resume.json', 100);

    Livewire::actingAs($user)
        ->test(CreateResumeImport::class)
        ->set('resumeImport.resume_file', $file)
        ->call('createForm');

    expect(session('custom_error'))->toBe('You can only have up to 5 resume imports. Please delete an old one first.');
    $this->assertDatabaseCount('resume_imports', 5);
});

test('user can edit name on an import', function () {
    $user = User::factory()->create();
    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => 'path.json',
        'file_name' => 'test.json',
        'status' => ProcessStatus::COMPLETED,
        'name' => 'Old Name',
    ]);

    Livewire::actingAs($user)
        ->test(EditResumeImport::class, ['resumeImportId' => $import->id])
        ->set('resumeImport.name', 'New Name')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    expect($import->fresh()->name)->toBe('New Name');
});
