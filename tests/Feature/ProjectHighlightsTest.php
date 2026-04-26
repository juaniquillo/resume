<?php

use App\Models\Project;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->project = Project::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from project highlights index', function () {
    $this->get(route('dashboard.projects.highlights', $this->project->id))
        ->assertRedirect(route('login'));
});

it('renders the project highlights index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.projects.highlights', $this->project->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.projects.highlights.index')
        ->assertViewHas('form');
});

it('stores a new project highlight', function () {
    $data = [
        'highlight' => 'Accomplished something great',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.projects.highlights.store', $this->project->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->project->id,
        'highlightable_type' => Project::class,
        'highlight' => 'Accomplished something great',
    ]);
});

it('renders the edit project highlight page', function () {
    $highlight = $this->project->highlights()->create([
        'highlight' => 'Old highlight',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.projects.highlights.edit', [$this->project->id, $highlight->id]))
        ->assertSuccessful()
        ->assertViewIs('dashboard.projects.highlights.edit')
        ->assertViewHas('form');
});

it('updates an existing project highlight', function () {
    $highlight = $this->project->highlights()->create([
        'highlight' => 'Old highlight',
    ]);

    $data = [
        'highlight' => 'New highlight',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.projects.highlights.update', [$this->project->id, $highlight->id]), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('highlights', [
        'id' => $highlight->id,
        'highlight' => 'New highlight',
    ]);
});

it('deletes a project highlight', function () {
    $highlight = $this->project->highlights()->create([
        'highlight' => 'To be deleted',
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.projects.highlights.destroy', [$this->project->id, $highlight->id]), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('highlights', [
        'id' => $highlight->id,
    ]);
});
