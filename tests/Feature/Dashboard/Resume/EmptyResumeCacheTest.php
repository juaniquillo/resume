<?php

use App\Cruds\Schema\Cache\EmptyResumeCacheCrud;
use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

pest()->group('fast');

it('clears the resume cache when clearCache is called', function () {
    $user = User::factory()->create();

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    // Simulate cached content
    Cache::forever($cacheKey, '<html>resume content</html>');

    expect(Cache::has($cacheKey))->toBeTrue();

    $presenter->clearCache();

    expect(Cache::has($cacheKey))->toBeFalse();
});

it('clears the cache via the EmptyResumeCacheCrud', function () {
    $user = User::factory()->create();

    // Mock authenticated user
    $this->actingAs($user);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();
    Cache::forever($cacheKey, 'content');

    $crud = new EmptyResumeCacheCrud;
    $crud->handleCacheClear();

    expect(Cache::has($cacheKey))->toBeFalse();
});
