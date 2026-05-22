<?php

use App\Models\User;

test('authenticated user can view slug update page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.slug'))
        ->assertOk()
        ->assertViewIs('dashboard.options.slug')
        ->assertSee('Resume Slug');
});

test('authenticated user can update their slug', function () {
    $user = User::factory()->create(['slug' => 'old-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.slug.update'), [
            'slug' => 'new-slug',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->fresh()->slug)->toBe('new-slug');
});

test('slug must be unique among users', function () {
    User::factory()->create(['slug' => 'taken-slug']);
    $user = User::factory()->create(['slug' => 'my-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('dashboard.resume.slug'))
        ->post(route('dashboard.resume.slug.update'), [
            'slug' => 'taken-slug',
        ])
        ->assertRedirect(route('dashboard.resume.slug'));

    $errors = session('errors')->getMessages();

    expect($errors)->toHaveKey('slug');
    expect($user->fresh()->slug)->toBe('my-slug');
});

test('user can keep their own slug when updating', function () {
    $user = User::factory()->create(['slug' => 'my-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->post(route('dashboard.resume.slug.update'), [
            'slug' => 'my-slug',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->fresh()->slug)->toBe('my-slug');
});

test('slug must follow alpha-dash format', function () {
    $user = User::factory()->create(['slug' => 'old-slug']);

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('dashboard.resume.slug'))
        ->post(route('dashboard.resume.slug.update'), [
            'slug' => 'invalid slug!',
        ])
        ->assertRedirect(route('dashboard.resume.slug'));
    // ->assertSessionHasErrors()

    $errors = session('errors')->getMessages();

    expect($errors)->toHaveKey('slug');
    expect($user->fresh()->slug)->toBe('old-slug');
});
