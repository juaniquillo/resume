<?php

use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('it rolls back all changes if a part of the import fails', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    // Valid basics, but invalid work (missing required company/name field)
    // Note: WorksCrud probably requires 'name' (which maps from 'company' in JSON Resume)
    $resumeData = [
        'basics' => [
            'name' => 'John Doe Rollback',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
        ],
        'work' => [
            [
                // Missing 'company' field which is required by WorksCrud
                'position' => 'Senior Developer',
            ],
        ],
    ];

    $filePath = 'imports/resumes/resume_failure.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'status' => 'pending',
    ]);

    $job = new ProcessResumeImport($import);
    $job->handle();

    $import->refresh();
    expect($import->status)->toBe('failed');
    expect($import->error)->toContain('The name field is required'); // WorksCrud maps 'company' to 'name'

    // Verify that NO basics were created (they should have been rolled back)
    $this->assertDatabaseMissing('basics', [
        'user_id' => $user->id,
        'name' => 'John Doe Rollback',
    ]);
});
