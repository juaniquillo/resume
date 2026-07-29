<?php

use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from skills index', function () {
    $this->get(route('dashboard.skills'))
        ->assertRedirect(route('login'));
});

it('renders the skills index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.skills'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.skills.index');
});
