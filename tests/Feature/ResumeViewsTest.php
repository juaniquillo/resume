<?php

use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it increments the view counter when a public resume is visited', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'slug' => 'test-user',
        'is_draft' => false,
        'views' => 0,
    ]);

    // Visit as guest
    $this->get(route('resume', 'test-user'))
        ->assertSuccessful();

    expect($user->generalOptions->fresh()->views)->toBe(1);

    // Visit again
    $this->get(route('resume', 'test-user'))
        ->assertSuccessful();

    expect($user->generalOptions->fresh()->views)->toBe(2);
});

test('it does not increment the view counter if the owner visits their own resume', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'slug' => 'test-user',
        'is_draft' => false,
        'views' => 0,
    ]);

    // Visit as owner
    $this->actingAs($user)
        ->get(route('resume', 'test-user'))
        ->assertSuccessful();

    expect($user->generalOptions->fresh()->views)->toBe(0);
});

test('it does not increment the view counter if the resume is in draft state', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    $user->generalOptions()->update([
        'slug' => 'test-user',
        'is_draft' => true,
        'views' => 0,
    ]);

    // Visit as guest
    $this->get(route('resume', 'test-user'))
        ->assertForbidden();

    expect($user->generalOptions->fresh()->views)->toBe(0);
});



