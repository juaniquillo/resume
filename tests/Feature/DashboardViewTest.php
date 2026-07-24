<?php

namespace Tests\Feature;

use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

pest()->group('fast');

test('it renders the onboarding view when basics are missing', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertStatus(200)
        ->assertSee('Welcome! Let\'s get started.')
        ->assertSee('Build Manually')
        ->assertSee('Import JSON')
        ->assertSee(route('dashboard.basics'))
        ->assertSee(route('dashboard.resume.import'));
});

test('it renders the standard dashboard view when basics are present', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertStatus(200)
        ->assertSee('Resume Manager')
        ->assertSee('Resume Sections')
        ->assertDontSee('Welcome! Let\'s get started.');
});



