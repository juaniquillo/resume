<?php

use App\Models\Basic;
use App\Models\Course;
use App\Models\Education;
use App\Models\Highlight;
use App\Models\Location;
use App\Models\Profile;
use App\Models\User;
use App\Models\Work;
use App\Presenters\ResumePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

pest()->group('slow');

test('modifying a Location invalidates resume cache', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->for($user)->create();
    $location = Location::factory()->create(['basic_id' => $basic->id]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();
    Cache::forever($cacheKey, 'content');

    $location->update(['city' => 'New City']);

    expect(Cache::has($cacheKey))->toBeFalse();
});

test('modifying a Profile invalidates resume cache', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->for($user)->create();
    $profile = Profile::factory()->create(['basic_id' => $basic->id]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();
    Cache::forever($cacheKey, 'content');

    $profile->update(['url' => 'https://example.com/new']);

    expect(Cache::has($cacheKey))->toBeFalse();
});

test('modifying a Highlight invalidates resume cache', function () {
    $user = User::factory()->create();
    $work = Work::factory()->for($user)->create();
    $highlight = Highlight::factory()->create([
        'highlightable_type' => Work::class,
        'highlightable_id' => $work->id,
    ]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();
    Cache::forever($cacheKey, 'content');

    $highlight->update(['highlight' => 'Updated Highlight']);

    expect(Cache::has($cacheKey))->toBeFalse();
});

test('modifying a Course invalidates resume cache', function () {
    $user = User::factory()->create();
    $education = Education::factory()->for($user)->create();
    $course = Course::factory()->create([
        'courseable_type' => Education::class,
        'courseable_id' => $education->id,
    ]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();
    Cache::forever($cacheKey, 'content');

    $course->update(['course' => 'Updated Course']);

    expect(Cache::has($cacheKey))->toBeFalse();
});
