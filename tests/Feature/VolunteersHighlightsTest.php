<?php

use App\Models\User;
use App\Models\Volunteer;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from volunteer highlights index', function () {
    $this->get(route('dashboard.volunteers.highlights', $this->volunteer->id))
        ->assertRedirect(route('login'));
});

it('renders the volunteer highlights index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers.highlights', $this->volunteer->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.volunteers.highlights.index');
});
