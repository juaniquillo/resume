<?php

use App\Models\Project;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from projects index', function () {
    $this->get(route('dashboard.projects'))
        ->assertRedirect(route('login'));
});

it('renders the projects index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.projects'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.projects.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the projects table when records exist', function () {
    Project::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.projects'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new project record', function () {
    $data = [
        'name' => 'Awesome Project',
        'start_date' => '2020-01-01',
        'end_date' => '2021-01-01',
        'url' => 'https://example.com',
        'description' => 'Doing cool things',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.projects.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('projects', [
        'user_id' => $this->user->id,
        'name' => 'Awesome Project',
        'url' => 'https://example.com',
    ]);
});

it('validates project data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.projects.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'start_date', 'end_date']);
});

it('renders the edit project page', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.projects.edit', $project->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.projects.edit')
        ->assertViewHas('form');
});

it('updates an existing project record', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Project',
    ]);

    $data = [
        'name' => 'New Project',
        'start_date' => '2020-01-01',
        'end_date' => '2021-01-01',
        'url' => 'https://new-example.com',
        'description' => 'Updated description',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.projects.update', $project->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'New Project',
    ]);
});

it('deletes a project record', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.projects.destroy', $project->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});
