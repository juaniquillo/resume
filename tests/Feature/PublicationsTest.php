<?php

use App\Models\Publication;
use App\Models\User;

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
        ->assertViewIs('dashboard.publications.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the publications table when records exist', function () {
    Publication::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.publications'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new publication record', function () {
    $data = [
        'name' => 'The Great Research',
        'date' => '2023-10-26',
        'issuer' => 'Tech Journal',
        'url' => 'https://example.com/publication',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.publications.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('publications', [
        'user_id' => $this->user->id,
        'name' => 'The Great Research',
        'issuer' => 'Tech Journal',
    ]);
});

it('validates publication data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.publications.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'date', 'issuer']);
});

it('renders the edit publication page', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.publications.edit', $publication->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.publications.edit')
        ->assertViewHas('form');
});

it('updates an existing publication record', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Publication',
    ]);

    $data = [
        'name' => 'New Publication Name',
        'date' => '2024-01-15',
        'issuer' => 'Science Review',
        'url' => 'https://new-url.com/pub',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.publications.update', $publication->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('publications', [
        'id' => $publication->id,
        'name' => 'New Publication Name',
    ]);
});

it('deletes a publication record', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.publications.destroy', $publication->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('publications', [
        'id' => $publication->id,
    ]);
});
