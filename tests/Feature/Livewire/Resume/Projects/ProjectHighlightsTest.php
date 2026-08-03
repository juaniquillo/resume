<?php

use App\Livewire\Resume\Highlights\CreateHighlight;
use App\Livewire\Resume\Highlights\DeleteHighlight;
use App\Livewire\Resume\Highlights\EditHighlight;
use App\Livewire\Resume\Highlights\HighlightTable;
use App\Models\Highlight;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

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
        ->assertViewIs('dashboard.projects.highlights.index');
});

it('renders the create project highlight component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->project])
        ->assertSuccessful();
});

it('creates a new project highlight successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->project])
        ->set('highlights.highlight', 'Project milestone achieved')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->project->id,
        'highlightable_type' => Project::class,
        'highlight' => 'Project milestone achieved',
    ]);
});

it('renders the edit project highlight component', function () {
    $highlight = Highlight::factory()->create([
        'highlightable_id' => $this->project->id,
        'highlightable_type' => Project::class,
        'highlight' => 'Old highlight',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $highlight->id])
        ->assertSuccessful();
});

it('updates an existing project highlight', function () {
    $highlight = Highlight::factory()->create([
        'highlightable_id' => $this->project->id,
        'highlightable_type' => Project::class,
        'highlight' => 'Old highlight',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $highlight->id])
        ->set('highlights.highlight', 'Updated project highlight')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'id' => $highlight->id,
        'highlight' => 'Updated project highlight',
    ]);
});

it('deletes a project highlight successfully', function () {
    $highlight = Highlight::factory()->create([
        'highlightable_id' => $this->project->id,
        'highlightable_type' => Project::class,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteHighlight::class, ['highlightId' => $highlight->id])
        ->call('deleteWHighlight')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('highlights', [
        'id' => $highlight->id,
    ]);
});

it('renders the project highlights table component', function () {
    $this->actingAs($this->user);

    Livewire::test(HighlightTable::class, ['model' => $this->project])
        ->assertSuccessful();
});
