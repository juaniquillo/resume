<?php

use App\Livewire\Resume\Highlights\EditHighlight;
use App\Models\Highlight;
use App\Models\User;
use App\Models\Work;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->work = Work::factory()->create(['user_id' => $this->user->id]);
    $this->highlight = Highlight::factory()->create([
        'highlightable_id' => $this->work->id,
        'highlightable_type' => Work::class,
        'highlight' => 'Old highlight',
    ]);
});

it('renders the edit highlight component', function () {
    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $this->highlight->id])
        ->assertSuccessful();
});

it('updates an existing highlight', function () {
    $this->actingAs($this->user);

    Livewire::test(EditHighlight::class, ['highlightId' => $this->highlight->id])
        ->set('highlights.highlight', 'New highlight')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'id' => $this->highlight->id,
        'highlight' => 'New highlight',
    ]);
});



