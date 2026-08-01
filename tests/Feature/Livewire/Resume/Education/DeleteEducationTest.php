<?php

use App\Livewire\Resume\Education\DeleteEducation;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an education record', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteEducation::class, ['educationId' => $education->id])
        ->call('deleteEducation')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('education', [
        'id' => $education->id,
    ]);
});
