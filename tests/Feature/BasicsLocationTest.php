<?php

use App\Models\Basic;
use App\Models\Location;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->basic = Basic::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from location index', function () {
    $this->get(route('dashboard.basics.location'))
        ->assertRedirect(route('login'));
});

it('renders the location index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.basics.location'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.location.index')
        ->assertViewHas('form');
});

it('stores a new location record', function () {
    $data = [
        'address' => '123 Main St',
        'postal_code' => '12345',
        'city' => 'Anytown',
        'country_code' => 'US',
        'region' => 'State',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.location.update'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('locations', [
        'basic_id' => $this->basic->id,
        'address' => '123 Main St',
        'city' => 'Anytown',
    ]);
});

it('updates an existing location record', function () {
    $location = Location::factory()->create([
        'basic_id' => $this->basic->id,
        'address' => 'Old Address',
    ]);

    $data = [
        'address' => 'New Address',
        'postal_code' => '54321',
        'city' => 'Othertown',
        'country_code' => 'CA',
        'region' => 'Province',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.location.update'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('locations', [
        'id' => $location->id,
        'address' => 'New Address',
    ]);
});

it('validates location data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.location.update'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['city', 'country_code']);
});
