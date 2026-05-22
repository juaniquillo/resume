<?php

use App\Models\Basic;
use App\Models\User;
use App\Models\Work;
use App\Presenters\Themes\DefaultPresenterTheme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

test('resume is cached after first hit', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    $cacheKey = "resume:{$user->id}:v2:".md5(DefaultPresenterTheme::class);

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

    // Should only have queries to find the user by slug and maybe session/auth,
    // but NO queries for basics, works, education, etc.
    foreach ($queries as $query) {
        $sql = strtolower($query['query']);
        expect($sql)->not->toContain('select * from "basics"');
        expect($sql)->not->toContain('select * from "works"');
    }
});

test('updating a model invalidates the cache by incrementing version', function () {
    $user = User::factory()->create();

    // Creating Basic triggers invalidation #1 (after GeneralOption #0)
    Basic::factory()->for($user)->create();

    // Creating Work triggers invalidation #2
    $work = Work::factory()->for($user)->create();

    // Version should be 3 (GeneralOption + Basic + Work)
    expect((int) Cache::get("resume:{$user->id}:v"))->toBe(3);

    // Warm up cache (uses v3)
    $this->get(route('resume', $user->slug))->assertSuccessful();

    $v3Key = "resume:{$user->id}:v3:".md5(DefaultPresenterTheme::class);
    expect(Cache::has($v3Key))->toBeTrue();

    // Update work entry triggers invalidation #3
    $work->update(['position' => 'Updated Position']);

    // Version should be 4
    expect((int) Cache::get("resume:{$user->id}:v"))->toBe(4);

    // Third hit should use v4 key
    $v4Key = "resume:{$user->id}:v4:".md5(DefaultPresenterTheme::class);
    $this->get(route('resume', $user->slug))->assertSuccessful();

    expect(Cache::has($v4Key))->toBeTrue();
});
