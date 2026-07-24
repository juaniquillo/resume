<?php

use App\Livewire\Resume\Works\CreateWork;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create work component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->assertSuccessful();
});

it('sets default values correctly', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->assertSet('works.name', '');
});

it('creates a new work record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->set('works.name', 'New Corp')
        ->set('works.position', 'Developer')
        ->set('works.starts_at', '2020-01')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('works', [
        'user_id' => $this->user->id,
        'name' => 'New Corp',
        'position' => 'Developer',
    ]);
});

it('validates required fields', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->set('works.name', '')
        ->call('createForm')
        ->assertHasErrors(['name']);
});



