<?php

use App\Enums\ResumeTheme;
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
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

pest()->group('cache');

test('it caches the resume for anonymous visitors and avoids redundant queries', function () {
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

    expect(Cache::has($cacheKey))->toBeFalse();

    // First visit: caches the resume
    $this->get(route('resume', $user->slug))->assertOk();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Second visit: should use cache and avoid loading relations
    DB::enableQueryLog();
    $this->get(route('resume', $user->slug))->assertOk();
    $queries = DB::getQueryLog();

    foreach ($queries as $query) {
        $sql = strtolower($query['query']);
        if (str_contains($sql, 'select exists')) {
            continue;
        }
        expect($sql)->not->toContain('select * from "works"');
    }
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

    $this->get(route('resume', $user->slug));
    expect(Cache::has($cacheKey))->toBeTrue();

    $this->get(route('resume', $user->slug));
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

    $this->get(route('resume', $user->slug));

    expect($user->generalOptions()->first()->fresh()->views)->toBe(1);
});

test('updating models and related records invalidates resume cache', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['is_draft' => false]);
    $basic = Basic::factory()->for($user)->create();
    $location = Location::factory()->create(['basic_id' => $basic->id]);
    $profile = Profile::factory()->create(['basic_id' => $basic->id]);
    $work = Work::factory()->for($user)->create();
    $highlight = Highlight::factory()->create([
        'highlightable_type' => Work::class,
        'highlightable_id' => $work->id,
    ]);
    $education = Education::factory()->for($user)->create();
    $course = Course::factory()->create([
        'courseable_type' => Education::class,
        'courseable_id' => $education->id,
    ]);

    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    // Warm up cache
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update basic
    $basic->update(['label' => 'Updated Label']);
    expect(Cache::has($cacheKey))->toBeFalse();

    // Re-warm
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update location
    $location->update(['city' => 'New City']);
    expect(Cache::has($cacheKey))->toBeFalse();

    // Re-warm
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update profile
    $profile->update(['url' => 'https://example.com/new']);
    expect(Cache::has($cacheKey))->toBeFalse();

    // Re-warm
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update highlight
    $highlight->update(['highlight' => 'Updated Highlight']);
    expect(Cache::has($cacheKey))->toBeFalse();

    // Re-warm
    $this->get(route('resume', $user->slug))->assertSuccessful();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update course
    $course->update(['course' => 'Updated Course']);
    expect(Cache::has($cacheKey))->toBeFalse();
});
