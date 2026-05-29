<?php

use App\Models\Basic;
use App\Models\User;
use App\Presenters\Resume\ResumeDataLoader;
use Illuminate\Support\Facades\DB;

pest()->group('fast');

test('resume data loader is a singleton in the container', function () {
    $instance1 = app(ResumeDataLoader::class);
    $instance2 = app(ResumeDataLoader::class);

    expect($instance1)->toBe($instance2);
});

test('resume data loader caches data per request', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    $loader = app(ResumeDataLoader::class);

    DB::enableQueryLog();

    // First load
    $loader->load($user);
    $queriesAfterFirstLoad = count(DB::getQueryLog());
    expect($queriesAfterFirstLoad)->toBeGreaterThan(0);

    // Second load should not trigger more queries for resume sections
    $loader->load($user);
    $queriesAfterSecondLoad = count(DB::getQueryLog());

    expect($queriesAfterSecondLoad)->toBe($queriesAfterFirstLoad);
});

test('resume data loader caches separately for different users', function () {
    $user1 = User::factory()->create();
    Basic::factory()->for($user1)->create();

    $user2 = User::factory()->create();
    Basic::factory()->for($user2)->create();

    $loader = app(ResumeDataLoader::class);

    DB::enableQueryLog();

    // Load user 1
    $loader->load($user1);
    $queriesAfterUser1 = count(DB::getQueryLog());

    // Load user 2 should trigger more queries
    $loader->load($user2);
    $queriesAfterUser2 = count(DB::getQueryLog());

    expect($queriesAfterUser2)->toBeGreaterThan($queriesAfterUser1);
});


