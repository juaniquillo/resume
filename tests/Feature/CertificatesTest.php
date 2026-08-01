<?php

use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from certificates index', function () {
    $this->get(route('dashboard.certificates'))
        ->assertRedirect(route('login'));
});

it('renders the certificates index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.certificates'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.certificates.index');
});
