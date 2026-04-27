<?php

use App\Models\User;
use App\Models\Volunteer;

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
        ->assertViewIs('dashboard.volunteers.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the volunteers table when records exist', function () {
    Volunteer::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new volunteer record', function () {
    $data = [
        'organization' => 'NGO Name',
        'position' => 'Volunteer Developer',
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
        'summary' => 'Helping others',
        'url' => 'https://example.org',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.volunteers.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('volunteers', [
        'user_id' => $this->user->id,
        'organization' => 'NGO Name',
        'position' => 'Volunteer Developer',
    ]);
});

it('validates volunteer data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.volunteers.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['organization', 'position', 'starts_at']);
});

it('renders the edit volunteer page', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers.edit', $volunteer->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.volunteers.edit')
        ->assertViewHas('form');
});

it('updates an existing volunteer record', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
        'organization' => 'Old NGO',
    ]);

    $data = [
        'organization' => 'New NGO',
        'position' => 'Senior Volunteer',
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
        'summary' => 'Updated summary',
        'url' => 'https://new-example.org',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.volunteers.update', $volunteer->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('volunteers', [
        'id' => $volunteer->id,
        'organization' => 'New NGO',
    ]);
});

it('deletes a volunteer record', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.volunteers.destroy', $volunteer->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('volunteers', [
        'id' => $volunteer->id,
    ]);
});
