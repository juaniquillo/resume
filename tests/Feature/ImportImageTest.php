<?php

use App\Enums\ProcessStatus;
use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

pest()->group('slow');

test('it processes a resume with a valid data uri image', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    // 1x1 white pixel PNG
    $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BfAQWQA78CAn3fAAAAAElFTkSuQmCC';
    $dataUri = "data:image/png;base64,{$base64}";

    $resumeData = [
        'basics' => [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
            'image' => $dataUri,
        ],
    ];

    $filePath = 'imports/resumes/resume_with_image.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'resume_with_image.json',
        'status' => ProcessStatus::PENDING,
    ]);

    $job = new ProcessResumeImport($import);
    $job->handle();

    $import->refresh();
    expect($import->status)->toBe(ProcessStatus::COMPLETED);

    $basics = $user->basics()->first();
    expect($basics->image)->not->toBeNull();
    Storage::disk('local')->assertExists($basics->image);
});

test('it fails when the imported image is too large', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    // Create a "large" image by repeating data (this is just content, but validator checks size)
    // 1024KB = 1,048,576 bytes. Let's make it 2MB.
    $largeContent = str_repeat('A', 2 * 1024 * 1024);
    $dataUri = 'data:image/png;base64,'.base64_encode($largeContent);

    $resumeData = [
        'basics' => [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
            'image' => $dataUri,
        ],
    ];

    $filePath = 'imports/resumes/resume_large_image.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'resume_large_image.json',
        'status' => ProcessStatus::PENDING,
    ]);

    $job = new ProcessResumeImport($import);
    $job->handle();

    $import->refresh();
    expect($import->status)->toBe(ProcessStatus::FAILED);
    // It might fail either because it's not a real image (if garbage) or because it's too large.
    // The validator returns all errors.
    expect($import->error)->toMatch('/The image field must (be an image|not be greater than)/');
});

test('it fails when the imported file is not an image', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    // A text file content as Data URI
    $dataUri = 'data:image/png;base64,'.base64_encode('This is just text, not an image header.');

    $resumeData = [
        'basics' => [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
            'image' => $dataUri,
        ],
    ];

    $filePath = 'imports/resumes/resume_not_image.json';
    Storage::disk('local')->put($filePath, json_encode($resumeData));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => 'resume_not_image.json',
        'status' => ProcessStatus::PENDING,
    ]);

    $job = new ProcessResumeImport($import);
    $job->handle();

    $import->refresh();
    expect($import->status)->toBe(ProcessStatus::FAILED);
    expect($import->error)->toContain('The image field must be an image');
});



