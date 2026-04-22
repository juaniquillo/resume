<?php

use App\Models\User;
use App\Models\Volunteer;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from volunteer highlights index', function () {
    $this->get(route('dashboard.volunteers.highlights', $this->volunteer->id))
        ->assertRedirect(route('login'));
});

it('renders the volunteer highlights index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers.highlights', $this->volunteer->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.volunteers.highlights.index')
        ->assertViewHas('form');
});

it('stores a new volunteer highlight', function () {
    $data = [
        'highlight' => 'Accomplished something great as a volunteer',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.volunteers.highlights.store', $this->volunteer->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->volunteer->id,
        'highlightable_type' => Volunteer::class,
        'highlight' => 'Accomplished something great as a volunteer',
    ]);
});

it('renders the edit volunteer highlight page', function () {
    $highlight = $this->volunteer->highlights()->create([
        'highlight' => 'Old volunteer highlight',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.volunteers.highlights.edit', [$this->volunteer->id, $highlight->id]))
        ->assertSuccessful()
        ->assertViewIs('dashboard.volunteers.highlights.edit')
        ->assertViewHas('form');
});

it('updates an existing volunteer highlight', function () {
    $highlight = $this->volunteer->highlights()->create([
        'highlight' => 'Old volunteer highlight',
    ]);

    $data = [
        'highlight' => 'New volunteer highlight',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.volunteers.highlights.update', [$this->volunteer->id, $highlight->id]), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'id' => $highlight->id,
        'highlight' => 'New volunteer highlight',
    ]);
});

it('deletes a volunteer highlight', function () {
    $highlight = $this->volunteer->highlights()->create([
        'highlight' => 'To be deleted',
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.volunteers.highlights.destroy', [$this->volunteer->id, $highlight->id]), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('highlights', [
        'id' => $highlight->id,
    ]);
});
