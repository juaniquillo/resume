<?php

use App\Livewire\Resume\Highlights\DeleteHighlight;
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
    ]);
});

it('deletes a highlight successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(DeleteHighlight::class, ['highlightId' => $this->highlight->id])
        ->call('deleteWHighlight')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('highlights', [
        'id' => $this->highlight->id,
    ]);
});



