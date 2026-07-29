<?php

use App\Livewire\Resume\Publications\CreatePublication;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create publication component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreatePublication::class)
        ->assertSuccessful();
});

it('creates a new publication record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreatePublication::class)
        ->set('publications.name', 'Research Paper')
        ->set('publications.date', '2023-10-26')
        ->set('publications.issuer', 'ACM')
        ->set('publications.url', 'https://example.com/paper')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('publications', [
        'user_id' => $this->user->id,
        'name' => 'Research Paper',
        'issuer' => 'ACM',
    ]);
});
