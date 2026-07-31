<?php

use App\Livewire\Resume\References\EditReference;
use App\Models\Reference;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit reference component', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditReference::class, ['referenceId' => $reference->id])
        ->assertSuccessful();
});

it('updates an existing reference record successfully', function () {
    $reference = Reference::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditReference::class, ['referenceId' => $reference->id])
        ->set('references.name', 'New Name')
        ->set('references.reference', 'Updated reference details.')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('references', [
        'id' => $reference->id,
        'name' => 'New Name',
    ]);
});
