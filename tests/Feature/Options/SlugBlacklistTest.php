<?php

use App\Enums\ResumeTheme;
use App\Enums\SlugBlacklist;
use App\Livewire\Options\UpdateGeneralOptions;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

test('blacklisted slugs are rejected', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', fake()->randomElement(SlugBlacklist::values()))
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertHasErrors(['slug']);

    expect($user->fresh()->generalOptions->slug)->toBe('old-slug');
});

test('valid slugs are accepted', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'old-slug']);

    Livewire::actingAs($user)
        ->test(UpdateGeneralOptions::class)
        ->set('generalOptions.slug', 'john-doe')
        ->set('generalOptions.theme', ResumeTheme::DEFAULT->value)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.resume.general'));

    expect($user->fresh()->generalOptions->slug)->toBe('john-doe');
});



