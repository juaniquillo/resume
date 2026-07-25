<?php

use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from education index', function () {
    $this->get(route('dashboard.education'))
        ->assertRedirect(route('login'));
});

it('renders the education index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.education'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.index');
});
