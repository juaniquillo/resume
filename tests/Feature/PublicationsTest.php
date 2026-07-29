<?php

use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from publications index', function () {
    $this->get(route('dashboard.publications'))
        ->assertRedirect(route('login'));
});

it('renders the publications index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.publications'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.publications.index');
});
