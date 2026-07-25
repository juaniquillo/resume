<?php

use App\Livewire\Resume\Highlights\EditHighlight;
use App\Models\Highlight;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->volunteer = Volunteer::factory()->create(['user_id' => $this->user->id]);
    $this->highlight = Highlight::factory()->create([
        'highlightable_id' => $this->volunteer->id,
        'highlightable_type' => Volunteer::class,
        'highlight' => 'Old volunteer highlight',
    ]);
});

it('renders the edit volunteer highlight component', function () {
    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $this->highlight->id])
        ->assertSuccessful();
});

it('updates an existing volunteer highlight', function () {
    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $this->highlight->id])
        ->set('highlights.highlight', 'New volunteer highlight')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'id' => $this->highlight->id,
        'highlight' => 'New volunteer highlight',
    ]);
});
