<?php

use App\Livewire\Resume\Publications\CreatePublication;
use App\Models\Publication;
use App\Models\User;
use App\Support\ResumeLimit;
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

it('publications records have a limit', function () {
    $this->actingAs($this->user);
    Publication::factory()->count(ResumeLimit::PUBLICATIONS)->create(['user_id' => $this->user->id]);

    Livewire::test(CreatePublication::class)
        ->set('publications.name', 'Extra Publication')
        ->set('publications.issuer', 'Extra Issuer')
        ->call('createForm');

    $this->assertDatabaseCount('publications', ResumeLimit::PUBLICATIONS);
});
