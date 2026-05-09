<?php

use App\Models\Reference;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from references index', function () {
    $this->get(route('dashboard.references'))
        ->assertRedirect(route('login'));
});

it('renders the references index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.references'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.references.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the references table when records exist', function () {
    Reference::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.references'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new reference record', function () {
    $data = [
        'name' => 'Jane Smith',
        'reference' => 'This is a test reference text.',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.references.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('references', [
        'user_id' => $this->user->id,
        'name' => 'Jane Smith',
        'reference' => 'This is a test reference text.',
    ]);
});

it('validates reference data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.references.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name']);
});

it('renders the edit reference page', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.references.edit', $reference->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.references.edit')
        ->assertViewHas('form');
});

it('updates an existing reference record', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
    ]);

    $data = [
        'name' => 'New Name',
        'reference' => 'Updated reference text.',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.references.update', $reference->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('references', [
        'id' => $reference->id,
        'name' => 'New Name',
        'reference' => 'Updated reference text.',
    ]);
});

it('deletes a reference record', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.references.destroy', $reference->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('references', [
        'id' => $reference->id,
    ]);
});
