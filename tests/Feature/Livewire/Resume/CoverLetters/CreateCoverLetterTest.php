<?php

use App\Livewire\Resume\CoverLetters\CreateCoverLetter;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create cover letter component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCoverLetter::class)
        ->assertSuccessful();
});

it('creates a new cover letter successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCoverLetter::class)
        ->set('coverLetters.title', 'Targeted Application')
        ->set('coverLetters.company', 'Awesome Corp')
        ->set('coverLetters.content', 'Dear Hiring Manager...')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('cover_letters', [
        'user_id' => $this->user->id,
        'title' => 'Targeted Application',
        'company' => 'Awesome Corp',
    ]);
});

it('validates required fields', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCoverLetter::class)
        ->set('coverLetters.title', '')
        ->call('createForm')
        ->assertHasErrors(['title']);
});
