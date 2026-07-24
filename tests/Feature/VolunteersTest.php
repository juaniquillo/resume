<?php

use App\Models\User;
use App\Models\Volunteer;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from volunteers index', function () {
    $this->get(route('dashboard.volunteers'))
        ->assertRedirect(route('login'));
});

it('renders the volunteers index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.volunteers.index');
});

it('deletes a volunteer record', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.volunteers.destroy', $volunteer->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('volunteers', [
        'id' => $volunteer->id,
    ]);
});
