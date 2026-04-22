<?php

use App\Models\User;
use App\Models\Work;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->work = Work::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from work highlights index', function () {
    $this->get(route('dashboard.works.highlights', $this->work->id))
        ->assertRedirect(route('login'));
});

it('renders the work highlights index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.works.highlights', $this->work->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.works.highlights.index')
        ->assertViewHas('form');
});

it('stores a new work highlight', function () {
    $data = [
        'highlight' => 'Accomplished something great',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.works.highlights.store', $this->work->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->work->id,
        'highlightable_type' => Work::class,
        'highlight' => 'Accomplished something great',
    ]);
});

it('renders the edit work highlight page', function () {
    $highlight = $this->work->highlights()->create([
        'highlight' => 'Old highlight',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.works.highlights.edit', [$this->work->id, $highlight->id]))
        ->assertSuccessful()
        ->assertViewIs('dashboard.works.highlights.edit')
        ->assertViewHas('form');
});

it('updates an existing work highlight', function () {
    $highlight = $this->work->highlights()->create([
        'highlight' => 'Old highlight',
    ]);

    $data = [
        'highlight' => 'New highlight',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.works.highlights.update', [$this->work->id, $highlight->id]), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'id' => $highlight->id,
        'highlight' => 'New highlight',
    ]);
});

it('deletes a work highlight', function () {
    $highlight = $this->work->highlights()->create([
        'highlight' => 'To be deleted',
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.works.highlights.destroy', [$this->work->id, $highlight->id]), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('highlights', [
        'id' => $highlight->id,
    ]);
});
