<?php

use App\Livewire\Resume\Projects\DeleteProject;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing project record successfully', function () {
    $project = Project::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteProject::class, ['projectId' => $project->id])
        ->call('deleteProject')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});
