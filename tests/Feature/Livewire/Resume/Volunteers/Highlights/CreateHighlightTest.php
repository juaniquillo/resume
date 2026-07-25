<?php

use App\Livewire\Resume\Highlights\CreateHighlight;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->volunteer = Volunteer::factory()->create(['user_id' => $this->user->id]);
});

it('renders the create volunteer highlight component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->volunteer])
        ->assertSuccessful();
});

it('creates a new volunteer highlight successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->volunteer])
        ->set('highlights.highlight', 'Volunteer achievement')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->volunteer->id,
        'highlightable_type' => Volunteer::class,
        'highlight' => 'Volunteer achievement',
    ]);
});
