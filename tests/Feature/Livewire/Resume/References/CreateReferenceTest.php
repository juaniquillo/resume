<?php

use App\Livewire\Resume\References\CreateReference;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create reference component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateReference::class)
        ->assertSuccessful();
});

it('creates a new reference record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateReference::class)
        ->set('references.name', 'Jane Doe')
        ->set('references.reference', 'Great colleague and mentor.')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('references', [
        'user_id' => $this->user->id,
        'name' => 'Jane Doe',
    ]);
});
