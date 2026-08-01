<?php

use App\Livewire\Resume\Interests\CreateInterest;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create interest component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateInterest::class)
        ->assertSuccessful();
});

it('creates a new interest record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateInterest::class)
        ->set('interests.name', 'Coding')
        ->set('interests.keywords', ['PHP', 'Laravel'])
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('interests', [
        'user_id' => $this->user->id,
        'name' => 'Coding',
    ]);
});
