<?php

use App\Actions\Resume\Export\Builders\MetaBuilder;
use App\Actions\Resume\Export\BuildResumeArray;
use App\Enums\ProcessStatus;
use App\Enums\ResumeTheme;
use App\Jobs\ProcessJsonExport;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('json export includes meta version, and options only if use custom options is true', function () {
    $user = User::factory()->create();
    Basic::factory()->create(['user_id' => $user->id]);
    $user->generalOptions()->update([
        'theme' => ResumeTheme::BOLD,
        'hide_email' => true,
    ]);

    $data = (new BuildResumeArray($user))->handle();

    expect($data)->toHaveKey('meta');
    expect($data['meta'])->toHaveKey('version', '1.0.0');
    expect($data['meta'])->not()->toHaveKey('options');
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

test('json export includes options when general options are explicitly provided', function () {
    $user = User::factory()->create();
    Basic::factory()->create(['user_id' => $user->id]);
    $user->generalOptions()->update([
        'theme' => ResumeTheme::BOLD,
        'hide_email' => true,
    ]);

    $data = (new BuildResumeArray($user, $user->generalOptions))->handle();

    expect($data)->toHaveKey('meta');
    expect($data['meta'])->toHaveKey('options')
        ->and($data['meta']['options'])->toHaveKey('hide_email', true);
});
