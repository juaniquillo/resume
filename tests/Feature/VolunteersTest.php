<?php

use App\Models\User;

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
