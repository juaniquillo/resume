<?php

use App\Livewire\Resume\Basics\UpdateLocation;
use App\Models\Basic;
use App\Models\Location;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

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
        ->assertViewHas('basics');
});

it('renders the UpdateLocation Livewire component', function () {
    Livewire::actingAs($this->user)
        ->test(UpdateLocation::class)
        ->assertStatus(200);
});

it('stores a new location record', function () {
    Livewire::actingAs($this->user)
        ->test(UpdateLocation::class)
        ->set('location.city', 'Anytown')
        ->set('location.country_code', 'US')
        ->set('location.address', '123 Main St')
        ->set('location.postal_code', '12345')
        ->set('location.region', 'State')
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics.location'));

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

    Livewire::actingAs($this->user)
        ->test(UpdateLocation::class)
        ->set('location.city', 'Othertown')
        ->set('location.country_code', 'CA')
        ->set('location.address', 'New Address')
        ->set('location.postal_code', '54321')
        ->set('location.region', 'Province')
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics.location'));

    $this->assertDatabaseHas('locations', [
        'id' => $location->id,
        'address' => 'New Address',
    ]);
});

it('validates location data', function () {
    Livewire::actingAs($this->user)
        ->test(UpdateLocation::class)
        ->set('location.city', '')
        ->set('location.country_code', '')
        ->call('updateForm')
        ->assertHasErrors(['city', 'country_code']);
});
