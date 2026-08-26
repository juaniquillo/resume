<?php

use App\Livewire\Resume\Projects\CreateProject;
use App\Models\Project;
use App\Models\User;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create project component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateProject::class)
        ->assertSuccessful();
});

it('creates a new project record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateProject::class)
        ->set('projects.name', 'Awesome Project')
        ->set('projects.start_date', '2020-01')
        ->set('projects.end_date', '2021-01')
        ->set('projects.url', 'https://example.com')
        ->set('projects.description', 'Cool project description')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('projects', [
        'user_id' => $this->user->id,
        'name' => 'Awesome Project',
    ]);
});

it('projects records have a limit', function () {
    $this->actingAs($this->user);
    Project::factory()->count(ResumeLimit::PROJECTS)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateProject::class)
        ->set('projects.name', 'Extra Project')
        ->set('projects.start_date', '2022-01')
        ->call('createForm');

    $this->assertDatabaseCount('projects', ResumeLimit::PROJECTS);
});
