<?php

use App\Livewire\Resume\Works\DeleteWork;
use App\Models\User;
use App\Models\Work;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes a work record', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteWork::class, ['workId' => $work->id])
        ->call('deleteWork')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('works', [
        'id' => $work->id,
    ]);
});



