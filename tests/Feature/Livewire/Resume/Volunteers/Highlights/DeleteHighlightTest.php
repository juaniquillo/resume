<?php

use App\Livewire\Resume\Highlights\DeleteHighlight;
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
    ]);
});

it('deletes a volunteer highlight successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(DeleteHighlight::class, ['highlightId' => $this->highlight->id])
        ->call('deleteWHighlight')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('highlights', [
        'id' => $this->highlight->id,
    ]);
});
