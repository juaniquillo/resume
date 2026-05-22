<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('authenticated user can view section visibility page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.visibility'))
        ->assertOk()
        ->assertViewIs('dashboard.options.visibility')
        ->assertSee('Section Visibility');
});

test('authenticated user can update section visibility', function () {
    $user = User::factory()->create();

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.visibility.update'), [
            'summary' => false,
            'work' => true,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $visibility = $user->fresh()->sectionVisibility;
    expect($visibility->settings['summary'])->toBe(false);
    expect($visibility->settings['work'])->toBe(true);
});

test('updating visibility invalidates the resume cache', function () {
    $user = User::factory()->create();
    $cacheKey = "resume:{$user->id}:v";

    Cache::forever($cacheKey, 1);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.visibility.update'), [
            'summary' => false,
        ]);

    expect((int) Cache::get($cacheKey))->toBe(2);
});
