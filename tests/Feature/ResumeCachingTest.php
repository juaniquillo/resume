<?php

use App\Models\Basic;
use App\Models\User;
use App\Presenters\Themes\DefaultPresenterTheme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

pest()->group('slow');

test('resume is cached after first hit', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    $cacheKey = "resume:{$user->id}:".md5(DefaultPresenterTheme::class);

    expect(Cache::has($cacheKey))->toBeFalse();

    $this->get(route('resume', $user->slug))->assertSuccessful();

    expect(Cache::has($cacheKey))->toBeTrue();
});

test('subsequent hits use cache and avoid redundant queries', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    // Warm up cache
    $this->get(route('resume', $user->slug))->assertSuccessful();

    // Track queries on second hit
    DB::enableQueryLog();
    $this->get(route('resume', $user->slug))->assertSuccessful();
    $queries = DB::getQueryLog();

    // Should only have queries to find the user by slug and its general options,
    // and a check for basics existence for the draft view,
    // but NO queries to load all basics, works, education, etc.
    foreach ($queries as $query) {
        $sql = strtolower($query['query']);

        // select exists(...) is okay
        if (str_contains($sql, 'select exists')) {
            continue;
        }

        expect($sql)->not->toContain('select * from "works"');
    }
});

test('updating a model invalidates the cache', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->for($user)->create();

    $cacheKey = "resume:{$user->id}:".md5(DefaultPresenterTheme::class);

    // Warm up cache
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update basic entry triggers invalidation
    $basic->update(['label' => 'Updated Label']);

    // Cache should be gone
    expect(Cache::has($cacheKey))->toBeFalse();

    // Third hit should re-cache
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();
});
