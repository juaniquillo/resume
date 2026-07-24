<?php

use App\Livewire\Resume\CoverLetters\CoverLetterForm;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the cover letter form component', function () {
    $this->actingAs($this->user);

    Livewire::test(CoverLetterForm::class)
        ->assertSuccessful();
});

it('saves a new cover letter successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CoverLetterForm::class)
        ->set('coverLetter.content', 'Dear Hiring Manager...')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('cover_letters', [
        'user_id' => $this->user->id,
        'content' => 'Dear Hiring Manager...',
    ]);
});

it('updates an existing cover letter successfully', function () {
    $this->actingAs($this->user);
    $this->user->coverLetters()->create(['content' => 'Original Content']);

    Livewire::test(CoverLetterForm::class)
        ->set('coverLetter.content', 'Updated Content')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('cover_letters', [
        'user_id' => $this->user->id,
        'content' => 'Updated Content',
    ]);
    $this->assertDatabaseCount('cover_letters', 1);
});



