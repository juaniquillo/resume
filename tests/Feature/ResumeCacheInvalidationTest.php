<?php

use App\Models\Basic;
use App\Models\Course;
use App\Models\Education;
use App\Models\Highlight;
use App\Models\Location;
use App\Models\Profile;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('modifying a Location invalidates resume cache', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->for($user)->create();
    $location = Location::factory()->create(['basic_id' => $basic->id]);

    $key = "resume:{$user->id}:v";
    $initialVersion = (int) Cache::get($key, 0);

    $location->update(['city' => 'New City']);

    expect((int) Cache::get($key))->toBe($initialVersion + 1);
});

test('modifying a Profile invalidates resume cache', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->for($user)->create();
    $profile = Profile::factory()->create(['basic_id' => $basic->id]);

    $key = "resume:{$user->id}:v";
    $initialVersion = (int) Cache::get($key, 0);

    $profile->update(['url' => 'https://example.com/new']);

    expect((int) Cache::get($key))->toBe($initialVersion + 1);
});

test('modifying a Highlight invalidates resume cache', function () {
    $user = User::factory()->create();
    $work = Work::factory()->for($user)->create();
    $highlight = Highlight::factory()->create([
        'highlightable_type' => Work::class,
        'highlightable_id' => $work->id,
    ]);

    $key = "resume:{$user->id}:v";
    $initialVersion = (int) Cache::get($key, 0);

    $highlight->update(['highlight' => 'Updated Highlight']);

    expect((int) Cache::get($key))->toBe($initialVersion + 1);
});

test('modifying a Course invalidates resume cache', function () {
    $user = User::factory()->create();
    $education = Education::factory()->for($user)->create();
    $course = Course::factory()->create([
        'courseable_type' => Education::class,
        'courseable_id' => $education->id,
    ]);

    $key = "resume:{$user->id}:v";
    $initialVersion = (int) Cache::get($key, 0);

    $course->update(['course' => 'Updated Course']);

    expect((int) Cache::get($key))->toBe($initialVersion + 1);
});
