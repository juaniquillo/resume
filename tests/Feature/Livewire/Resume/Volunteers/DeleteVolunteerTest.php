<?php

use App\Livewire\Resume\Volunteers\DeleteVolunteer;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes a volunteer record', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteVolunteer::class, ['volunteerId' => $volunteer->id])
        ->call('deleteVolunteer')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('volunteers', [
        'id' => $volunteer->id,
    ]);
});
