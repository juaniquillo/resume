<?php

use App\Livewire\Resume\Projects\ProjectsTable;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the projects table component', function () {
    $this->actingAs($this->user);

    Livewire::test(ProjectsTable::class)
        ->assertSuccessful();
});

it('renders projects in the table when records exist', function () {
    Project::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(ProjectsTable::class)
        ->assertSuccessful();
});
