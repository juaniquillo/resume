<?php

use App\Enums\ResumeTheme;
use App\Livewire\Options\UpdateGeneralOptions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

pest()->group('fast');

test('authenticated user can update their slug and theme', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug', 'theme' => ResumeTheme::DEFAULT->value]);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'new-slug')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.resume.general'));

    $options = $user->fresh()->generalOptions;
    expect($options->slug)->toBe('new-slug');
    expect($options->theme)->toBe(ResumeTheme::DEFAULT);
});

test('slug must be unique among options', function () {
    $user1 = User::factory()->create();
    $user1->generalOptions()->update(['slug' => 'taken-slug']);

    $user2 = User::factory()->create();
    $user2->generalOptions()->update(['slug' => 'my-slug']);

    Livewire::actingAs($user2)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'taken-slug')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertHasErrors(['slug']);
});

test('user can keep their own slug when updating', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'my-slug']);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'my-slug')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.resume.general'));

    expect($user->fresh()->generalOptions->slug)->toBe('my-slug');
});

test('slug must follow alpha-dash format', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'invalid slug!')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertHasErrors(['slug']);

    expect($user->fresh()->generalOptions->slug)->toBe('old-slug');
});

test('authenticated user can update draft mode', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['is_draft' => false]);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'test-slug')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->set('generalOptions.is_draft', true)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.resume.general'));

    $options = $user->fresh()->generalOptions;
    expect($options->is_draft)->toBeTrue();
});

test('authenticated user can update security options', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update([
        'hide_phone' => false,
        'hide_email' => false,
        'hide_image' => false,
        'hide_address' => false,
    ]);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'test-slug')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->set('generalOptions.hide_phone', true)
        ->set('generalOptions.hide_email', true)
        ->set('generalOptions.hide_image', true)
        ->set('generalOptions.hide_address', true)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.resume.general'));

    $options = $user->fresh()->generalOptions;
    expect($options->hide_phone)->toBeTrue();
    expect($options->hide_email)->toBeTrue();
    expect($options->hide_image)->toBeTrue();
    expect($options->hide_address)->toBeTrue();
});
