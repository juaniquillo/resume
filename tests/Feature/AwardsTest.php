<?php

use App\Models\Award;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from awards index', function () {
    $this->get(route('dashboard.awards'))
        ->assertRedirect(route('login'));
});

it('renders the awards index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.awards'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.awards.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the awards table when records exist', function () {
    Award::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.awards'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new award record', function () {
    $data = [
        'title' => 'Best Employee of the Year',
        'awarder' => 'Company Name',
        'awarded_at' => '2023-10-26',
        'summary' => 'Recognized for outstanding performance.',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.awards.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('awards', [
        'user_id' => $this->user->id,
        'title' => 'Best Employee of the Year',
        'awarder' => 'Company Name',
    ]);
});

it('validates award data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.awards.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['title', 'awarder', 'awarded_at']);
});

it('renders the edit award page', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.awards.edit', $award->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.awards.edit')
        ->assertViewHas('form');
});

it('updates an existing award record', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Old Award',
    ]);

    $data = [
        'title' => 'New Award Title',
        'awarder' => 'New Awarder',
        'awarded_at' => '2024-01-15',
        'summary' => 'Updated summary for the award.',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.awards.update', $award->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('awards', [
        'id' => $award->id,
        'title' => 'New Award Title',
    ]);
});

it('deletes an award record', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.awards.destroy', $award->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('awards', [
        'id' => $award->id,
    ]);
});
