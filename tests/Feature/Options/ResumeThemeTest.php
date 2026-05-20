<?php

use App\Enums\ResumeTheme;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('authenticated user can view theme picker page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.theme'))
        ->assertOk()
        ->assertViewIs('dashboard.options.theme')
        ->assertSee('Choose Resume Theme');
});

test('authenticated user can update theme selection', function () {
    $user = User::factory()->create();

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.theme.update'), [
            'theme' => ResumeTheme::TAILWIND->value,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $themeRelation = $user->fresh()->theme;
    expect($themeRelation->theme)->toBe(ResumeTheme::TAILWIND->value);
    expect(ResumeTheme::from($themeRelation->theme))->toBe(ResumeTheme::TAILWIND);
});

test('updating theme selection invalidates the resume cache', function () {
    $user = User::factory()->create();
    $cacheKey = "resume:{$user->id}:v";

    Cache::forever($cacheKey, 1);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.theme.update'), [
            'theme' => ResumeTheme::TAILWIND->value,
        ]);

    expect((int) Cache::get($cacheKey))->toBe(2);
});
