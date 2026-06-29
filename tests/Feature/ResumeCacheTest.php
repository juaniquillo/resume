<?php

use App\Enums\ResumeTheme;
use App\Models\Basic;
use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

pest()->group('fast');

test('it caches the resume for anonymous visitors', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'views' => 0,
        'theme' => ResumeTheme::DEFAULT,
        'slug' => 'test-slug',
        'is_draft' => false,
    ]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    $this->get(route('resume', $user->slug))
        ->assertOk();

    expect(Cache::has($cacheKey))->toBeTrue();
});

test('anonymous visits do not bust the cache', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'views' => 0,
        'theme' => ResumeTheme::DEFAULT,
        'slug' => 'test-slug',
        'is_draft' => false,
    ]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    // First visit: caches the resume
    $this->get(route('resume', $user->slug));
    expect(Cache::has($cacheKey))->toBeTrue();

    // Second visit: should use cache
    $this->get(route('resume', $user->slug));

    // Verify cache still exists (was not busted by view increment)
    expect(Cache::has($cacheKey))->toBeTrue();
});

test('it eventually increments views for anonymous visitors', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'views' => 0,
        'theme' => ResumeTheme::DEFAULT,
        'slug' => 'test-slug',
        'is_draft' => false,
    ]);

    // Perform visit
    $this->get(route('resume', $user->slug));

    // Defer is automatic in the app,
    // for now we'll just check it increments via DB
    expect($user->generalOptions()->first()->fresh()->views)->toBe(1);
});
