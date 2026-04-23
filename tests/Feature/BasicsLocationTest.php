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

it('redirects guests to login from locations index', function () {
    $this->get(route('dashboard.basics.locations'))
        ->assertRedirect(route('login'));
});

it('renders the locations index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.basics.locations'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.locations.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the locations table when records exist', function () {
    Location::factory()->count(3)->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics.locations'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
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
        ->post(route('dashboard.basics.locations.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('locations', [
        'basic_id' => $this->basic->id,
        'address' => '123 Main St',
        'city' => 'Anytown',
    ]);
});

it('validates location data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.locations.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['address', 'postal_code', 'city', 'country_code']);
});

it('renders the edit location page', function () {
    $location = Location::factory()->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics.locations.edit', $location->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.locations.edit')
        ->assertViewHas('form');
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
        ->post(route('dashboard.basics.locations.update', $location->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('locations', [
        'id' => $location->id,
        'address' => 'New Address',
    ]);
});

it('deletes a location record', function () {
    $location = Location::factory()->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.basics.locations.destroy', $location->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('locations', [
        'id' => $location->id,
    ]);
});
