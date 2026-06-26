<?php

use App\Enums\ResumeTheme;
use App\Enums\SlugBlacklist;
use App\Models\User;

pest()->group('fast');

test('blacklisted slugs are rejected', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('dashboard.resume.general'))
        ->post(route('dashboard.resume.general.update'), [
            'slug' => fake()->randomElement(SlugBlacklist::values()),
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect(route('dashboard.resume.general'));

    $errors = session('errors')->getMessages();

    expect($errors)->toHaveKey('slug');
    expect($user->fresh()->generalOptions->slug)->toBe('old-slug');
});

test('valid slugs are accepted', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'john-doe',
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->fresh()->generalOptions->slug)->toBe('john-doe');
});
