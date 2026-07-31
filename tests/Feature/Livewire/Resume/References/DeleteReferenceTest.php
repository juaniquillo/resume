<?php

use App\Livewire\Resume\References\DeleteReference;
use App\Models\Reference;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing reference record successfully', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteReference::class, ['referenceId' => $reference->id])
        ->call('deleteReference')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('references', [
        'id' => $reference->id,
    ]);
});
