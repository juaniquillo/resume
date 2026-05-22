<?php

use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('authenticated user can access resume import page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard.resume.import'));

    $response->assertStatus(200);
});

test('user can upload a resume json file', function () {
    $this->withoutMiddleware();
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

    $response = $this->actingAs($user)->post(route('dashboard.resume.import.store'), [
        'resume_file' => $file,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('resume_imports', [
        'user_id' => $user->id,
        'file_name' => 'resume.json',
        'status' => 'pending',
    ]);

    $import = ResumeImport::first();
    Storage::disk('local')->assertExists($import->file_path);

    Queue::assertPushed(ProcessResumeImport::class, function ($job) use ($import) {
        return $job->import->id === $import->id;
    });
});

test('user cannot upload a resume with invalid json schema', function () {
    $this->withoutMiddleware();
    $this->withoutExceptionHandling();
    Queue::fake();
    Storage::fake('local');

    $user = User::factory()->create();
    $invalidData = ['invalid' => 'data'];
    $file = UploadedFile::fake()->createWithContent('resume.json', json_encode($invalidData));

    try {
        $this->actingAs($user)->post(route('dashboard.resume.import.store'), [
            'resume_file' => $file,
        ]);
        $this->fail('ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('resume_file');
        expect($e->errors()['resume_file'][0])->toBe('The provided file does not conform to the JSON Resume schema.');
    }

    $this->assertDatabaseCount('resume_imports', 0);
    Queue::assertNothingPushed();
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
        'status' => 'pending',
    ]);

    (new ProcessResumeImport($import))->handle();

    $import->refresh(); // Refresh the model to get the latest status and error

    $this->assertEquals('completed', $import->status, "Import job failed: {$import->error}");

    $user = $user->refresh();

    $this->assertDatabaseHas('resume_imports', [
        'id' => $import->id,
        'status' => 'completed',
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
    $this->withoutMiddleware();
    Storage::fake('local');
    $user = User::factory()->create();
    $filePath = 'imports/resumes/test.json';
    Storage::disk('local')->put($filePath, 'content');

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'test.json',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.resume.import.destroy', $import->id));

    $response->assertRedirect();
    $this->assertDatabaseMissing('resume_imports', ['id' => $import->id]);
    Storage::disk('local')->assertMissing($filePath);
});

test('user cannot delete another users resume import', function () {
    $this->withoutMiddleware();
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $import = ResumeImport::create([
        'user_id' => $otherUser->id,
        'file_path' => 'path/to/file.json',
        'file_name' => 'file.json',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.resume.import.destroy', $import->id));

    $response->assertStatus(404);
    $this->assertDatabaseHas('resume_imports', ['id' => $import->id]);
});

test('user cannot have more than 5 resume imports', function () {
    $this->withoutMiddleware();
    $user = User::factory()->create();
    ResumeImport::factory()->count(5)->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->create('new_resume.json', 100);

    $response = $this->actingAs($user)->post(route('dashboard.resume.import.store'), [
        'resume_file' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You can only have up to 5 resume imports. Please delete an old one first.');
    $this->assertDatabaseCount('resume_imports', 5);
});
