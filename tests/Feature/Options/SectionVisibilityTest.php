<?php

use App\Livewire\Options\SectionVisibility;
use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

pest()->group('slow');

test('authenticated user can view section visibility page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.visibility'))
        ->assertOk()
        ->assertSee('Section Visibility');
});

test('authenticated user can update section visibility', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SectionVisibility::class)
        ->set('sectionVisibility.summary', 0) // Boolean inputs in forms often come as 0/1 strings/ints
        ->set('sectionVisibility.work', 1)
        ->call('updateForm')
        ->assertHasNoErrors();

    $visibility = $user->fresh()->sectionVisibility;
    expect((bool) $visibility->settings['summary'])->toBe(false);
    expect((bool) $visibility->settings['work'])->toBe(true);
});

test('updating visibility invalidates the resume cache', function () {
    $user = User::factory()->create();
    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    Cache::forever($cacheKey, 'cached_content');

    Livewire::actingAs($user)
        ->test(SectionVisibility::class)
        ->set('sectionVisibility.summary', false)
        ->call('updateForm');

    expect(Cache::has($cacheKey))->toBeFalse();
});



