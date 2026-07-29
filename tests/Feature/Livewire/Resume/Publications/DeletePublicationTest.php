<?php

use App\Livewire\Resume\Publications\DeletePublication;
use App\Models\Publication;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing publication record successfully', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeletePublication::class, ['publicationId' => $publication->id])
        ->call('deletePublication')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('publications', [
        'id' => $publication->id,
    ]);
});
