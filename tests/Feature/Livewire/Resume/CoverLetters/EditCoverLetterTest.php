<?php

use App\Livewire\Resume\CoverLetters\EditCoverLetter;
use App\Models\CoverLetter;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->coverLetter = CoverLetter::factory()->create(['user_id' => $this->user->id]);
});

it('renders the edit cover letter component', function () {
    $this->actingAs($this->user);

    Livewire::test(EditCoverLetter::class, ['coverLetterId' => $this->coverLetter->id])
        ->assertSuccessful();
});

it('updates an existing cover letter', function () {
    $this->actingAs($this->user);

    Livewire::test(EditCoverLetter::class, ['coverLetterId' => $this->coverLetter->id])
        ->set('coverLetters.title', 'Updated Title')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('cover_letters', [
        'id' => $this->coverLetter->id,
        'title' => 'Updated Title',
    ]);
});
