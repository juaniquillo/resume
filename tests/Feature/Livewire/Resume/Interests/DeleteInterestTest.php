<?php

use App\Livewire\Resume\Interests\DeleteInterest;
use App\Models\Interest;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing interest record successfully', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteInterest::class, ['interestId' => $interest->id])
        ->call('deleteInterest')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('interests', [
        'id' => $interest->id,
    ]);
});
