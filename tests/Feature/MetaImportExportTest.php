<?php

use App\Actions\Resume\Export\Builders\MetaBuilder;
use App\Actions\Resume\Export\BuildResumeArray;
use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Enums\ResumeTheme;
use App\Jobs\ProcessJsonExport;
use App\Jobs\ProcessResumeImport;
use App\Models\Basic;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('json export includes meta version and options', function () {
    $user = User::factory()->create();
    Basic::factory()->create(['user_id' => $user->id]);
    $user->generalOptions()->update([
        'theme' => ResumeTheme::BOLD,
        'hide_email' => true,
    ]);

    $data = (new BuildResumeArray($user))->handle();

    expect($data)->toHaveKey('meta');
    expect($data['meta'])->toHaveKey('version', '1.0.0');
    expect($data['meta'])->toHaveKey('options');
    expect($data['meta']['options']['theme'])->toBe(ResumeTheme::BOLD);
    expect($data['meta']['options'])->toHaveKey('hide_email', true);
    expect($data['meta']['options'])->not->toHaveKey('slug');
    expect($data['meta']['options'])->not->toHaveKey('is_draft');
});

test('meta processor can apply or skip options based on parameter', function () {
    $user = User::factory()->create();
    $exportUser = User::factory()->create();
    Basic::factory()->create(['user_id' => $exportUser->id]);

    $exportUser->generalOptions()->update([
        'theme' => ResumeTheme::ELEGANT->value,
        'hide_phone' => true,
    ]);

    $data = (new BuildResumeArray($exportUser))->handle();

    Storage::fake('local');
    $path = 'imports/test-import.json';
    Storage::put($path, json_encode($data));

    // Test with applyMetaOptions = false
    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $path,
        'file_name' => 'test-import.json',
        'status' => ProcessStatus::PENDING,
    ]);

    (new ProcessResumeImport($import, applyMetaOptions: false))->handle();
    $userOptions = $user->fresh()->generalOptions;
    expect($userOptions === null || $userOptions->theme?->value !== 'elegant')->toBeTrue();

    // Test with applyMetaOptions = true on a fresh user
    $user2 = User::factory()->create();
    $import2 = ResumeImport::create([
        'user_id' => $user2->id,
        'file_path' => $path,
        'file_name' => 'test-import.json',
        'status' => ProcessStatus::PENDING,
    ]);

    (new ProcessResumeImport($import2, applyMetaOptions: true))->handle();

    $options = $user2->fresh()->generalOptions;

    expect($options)->not->toBeNull();
    expect($options->theme?->value ?? $options->theme)->toBe('elegant');
    expect($options->hide_phone)->toBeTrue();
});

test('meta builder can apply custom options to an export', function () {
    $user = User::factory()->create();

    $theme = ResumeTheme::BOLD;

    $user->generalOptions()->update([
        'theme' => $theme->value,
        'hide_email' => true,
    ]);

    $metaArray = (new MetaBuilder)->handle($user->generalOptions);

    expect($metaArray)->toHaveKey('options')
        ->and($metaArray['options'])->toHaveKey('theme', $theme)
        ->and($metaArray['options'])->toHaveKey('hide_email', true)
        ->and($metaArray['options'])->not->toHaveKey('slug')
        ->and($metaArray['options'])->not->toHaveKey('is_draft');
});


test('process json export job can apply custom options to an export', function () {
    
    Queue::fake();

    $user = User::factory()->create();

    $customOptions = [
        'hide_phone' => true,
    ];

    $theme = ResumeTheme::ELEGANT;

    $export = $user->resumeExports()->create([
        'name' => 'Test Export',
        'type' => 'json',
        'theme' => $theme->value,
        'custom_options' => $customOptions,
        'allow_download' => true,
        'status' => ProcessStatus::PENDING->value,
    ]);

    $export->type->dispatchExportJob($export);

    expect(Queue::hasPushed(ProcessJsonExport::class))->toBeTrue();
    Queue::assertPushed(ProcessJsonExport::class, function ($job) use ($export) {
        return $job->export->id === $export->id
            && $job->export->theme === $export->theme
            && $job->export->custom_options === $export->custom_options;
    });
});