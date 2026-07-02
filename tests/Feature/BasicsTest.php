<?php

use App\Models\Basic;
use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login', function () {
    $this->get(route('dashboard.basics'))
        ->assertRedirect(route('login'));
});

it('renders the basics index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.basics'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.index')
        ->assertSeeLivewire('basics.update-basics');
});

it('identifies when it is not the first time (model exists)', function () {
    Basic::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics'))
        ->assertSuccessful();
});
