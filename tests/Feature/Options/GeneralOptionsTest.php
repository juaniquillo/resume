<?php

use App\Enums\ResumeTheme;`r`npest()->group('fast');
use App\Models\User;`r`npest()->group('fast');

test('authenticated user can view general options page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.general'))
        ->assertOk()
        ->assertViewIs('dashboard.options.general')
        ->assertSee('General Options');
});

test('authenticated user can update their slug and theme', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug', 'theme' => ResumeTheme::DEFAULT->value]);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'new-slug',
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $options = $user->fresh()->generalOptions;
    expect($options->slug)->toBe('new-slug');
    expect($options->theme)->toBe(ResumeTheme::DEFAULT->value);
});

test('slug must be unique among options', function () {
    $user1 = User::factory()->create();
    $user1->generalOptions()->update(['slug' => 'taken-slug']);

    $user2 = User::factory()->create();
    $user2->generalOptions()->update(['slug' => 'my-slug']);

    $this->withoutMiddleware()
        ->actingAs($user2)
        ->from(route('dashboard.resume.general'))
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'taken-slug',
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect(route('dashboard.resume.general'));

    $errors = session('errors')->getMessages();

    expect($errors)->toHaveKey('slug');
    expect($user2->fresh()->generalOptions->slug)->toBe('my-slug');
});

test('user can keep their own slug when updating', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'my-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'my-slug',
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->fresh()->generalOptions->slug)->toBe('my-slug');
});

test('slug must follow alpha-dash format', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('dashboard.resume.general'))
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'invalid slug!',
            'theme' => ResumeTheme::DEFAULT->value,
        ])
        ->assertRedirect(route('dashboard.resume.general'));

    $errors = session('errors')->getMessages();

    expect($errors)->toHaveKey('slug');
    expect($user->fresh()->generalOptions->slug)->toBe('old-slug');
});

test('authenticated user can update draft mode', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['is_draft' => false]);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.general.update'), [
            'slug' => 'test-slug',
            'theme' => ResumeTheme::DEFAULT->value,
            'is_draft' => '1',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $options = $user->fresh()->generalOptions;
    expect((string) $options->is_draft)->toBe('1');
});


