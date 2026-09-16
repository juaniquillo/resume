<?php

use App\Actions\Resume\Export\BuildResumeArray;
use App\Enums\ProcessStatus;
use App\Enums\ResumeTheme;
use App\Jobs\ProcessResumeImport;
use App\Models\Basic;
use App\Models\GeneralOption;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('json export includes meta version and options', function () {
    $user = User::factory()->create();
    Basic::factory()->create(['user_id' => $user->id]);
    $user->generalOptions()->update([
        'theme' => ResumeTheme::BOLD->value,
        'hide_email' => true,
    ]);

    $data = (new BuildResumeArray($user))->handle();

    expect($data)->toHaveKey('meta');
    expect($data['meta'])->toHaveKey('version', '1.0.0');
    expect($data['meta'])->toHaveKey('options');
    expect($data['meta']['options']['theme'])->toBe(ResumeTheme::BOLD);
    expect($data['meta']['options'])->toHaveKey('hide_email', true);
});

test('meta processor can apply or skip options based on parameter', function () {
    $user = User::factory()->withGeneralOptions()->create();
    $exportUser = User::factory()->withGeneralOptions()->create();
    Basic::factory()->create(['user_id' => $exportUser->id]);
    $exportUser->generalOptions()->update([
        'theme' => 'elegant',
        'hide_phone' => true,
    ]);

    $data = (new BuildResumeArray($exportUser))->handle();

    Storage::fake('local');
    $path = 'imports/test-import.json';
    Storage::put($path, json_encode($data));

    $import = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $path,
        'file_name' => 'test-import.json',
        'status' => ProcessStatus::PENDING,
    ]);

    // Test with applyMetaOptions = false
    (new ProcessResumeImport($import, applyMetaOptions: false))->handle();
    $userOptions = $user->fresh()->generalOptions;
    expect($userOptions->theme?->value ?? $userOptions->theme)->not->toBe('elegant');

    // Test with applyMetaOptions = true
    $import2 = ResumeImport::create([
        'user_id' => $user->id,
        'file_path' => $path,
        'file_name' => 'test-import.json',
        'status' => ProcessStatus::PENDING,
    ]);

    (new ProcessResumeImport($import2, applyMetaOptions: true))->handle();
    $options = GeneralOption::where('user_id', $user->id)->first();
    expect($options)->not->toBeNull();
    expect($options->theme?->value ?? $options->theme)->toBe('elegant');
    expect($options->hide_phone)->toBeTrue();
});
