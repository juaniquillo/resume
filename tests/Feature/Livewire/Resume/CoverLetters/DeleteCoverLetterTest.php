<?php

use App\Livewire\Resume\CoverLetters\DeleteCoverLetter;
use App\Models\CoverLetter;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->coverLetter = CoverLetter::factory()->create(['user_id' => $this->user->id]);
});

it('deletes a cover letter successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(DeleteCoverLetter::class, ['coverLetterId' => $this->coverLetter->id])
        ->call('deleteCoverLetter')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('cover_letters', [
        'id' => $this->coverLetter->id,
    ]);
});
