<?php

use App\Livewire\Resume\Projects\EditProject;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit project component', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditProject::class, ['projectId' => $project->id])
        ->assertSuccessful();
});

it('updates an existing project record successfully', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Project',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditProject::class, ['projectId' => $project->id])
        ->set('projects.name', 'New Project Name')
        ->set('projects.start_date', '2022-01')
        ->set('projects.end_date', '2023-01')
        ->set('projects.url', 'https://new-example.com')
        ->set('projects.description', 'Updated project description')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'New Project Name',
    ]);
});
