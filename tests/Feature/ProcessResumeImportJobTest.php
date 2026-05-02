<?php

use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('it processes a resume json and creates database records', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $resumeData = [
        'basics' => [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'website' => 'https://johndoe.com',
            'summary' => 'A passionate software engineer.',
            'location' => [
                'address' => '123 Main St',
                'postalCode' => '12345',
                'city' => 'Anytown',
                'countryCode' => 'US',
                'region' => 'CA',
            ],
            'profiles' => [
                [
                    'network' => 'twitter',
                    'username' => 'johndoe',
                    'url' => 'https://twitter.com/johndoe',
                ],
            ],
        ],
        'work' => [
            [
                'company' => 'Tech Corp',
                'position' => 'Senior Developer',
                'startDate' => '2020-01-01',
                'summary' => 'Worked on various projects.',
                'highlights' => [
                    'Built a large scale system',
                ],
            ],
        ],
        'education' => [
            [
                'institution' => 'University of Technology',
                'area' => 'Computer Science',
                'studyType' => 'bachelor_degree',
                'startDate' => '2016-09-01',
                'endDate' => '2020-06-01',
            ],
        ],
    ];

    $filePath = 'imports/resumes/resume.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'status' => 'pending',
    ]);

    $job = new ProcessResumeImport($import);
    $job->handle();

    $import->refresh();
    if ($import->status === 'failed') {
        dump($import->error);
    }
    expect($import->status)->toBe('completed');

    // Assert Basics
    $this->assertDatabaseHas('basics', [
        'user_id' => $user->id,
        'name' => 'John Doe',
        'label' => 'Software Engineer',
    ]);

    $basics = $user->basics()->first();
    $this->assertDatabaseHas('locations', [
        'basic_id' => $basics->id,
        'address' => '123 Main St',
    ]);

    $this->assertDatabaseHas('profiles', [
        'basic_id' => $basics->id,
        'network' => 'twitter',
    ]);

    // Assert Work
    $this->assertDatabaseHas('works', [
        'user_id' => $user->id,
        'name' => 'Tech Corp',
        'position' => 'Senior Developer',
    ]);

    $work = $user->works()->first();
    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $work->id,
        'highlightable_type' => Work::class,
        'highlight' => 'Built a large scale system',
    ]);

    // Assert Education
    $this->assertDatabaseHas('education', [
        'user_id' => $user->id,
        'institution' => 'University of Technology',
    ]);
});
