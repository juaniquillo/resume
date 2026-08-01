<?php

use App\Livewire\Resume\Awards\DeleteAward;
use App\Models\Award;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an award record successfully', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteAward::class, ['awardId' => $award->id])
        ->call('deleteAward')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('awards', [
        'id' => $award->id,
    ]);
});
