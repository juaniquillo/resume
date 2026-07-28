<?php

use App\Livewire\Resume\Awards\EditAward;
use App\Models\Award;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit award component', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditAward::class, ['awardId' => $award->id])
        ->assertSuccessful();
});

it('updates an existing award record successfully', function () {
    $award = Award::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Old Title',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditAward::class, ['awardId' => $award->id])
        ->set('awards.title', 'New Award Title')
        ->set('awards.awarder', 'New Awarder')
        ->set('awards.awarded_at', '2024-01-15')
        ->set('awards.summary', 'Updated summary.')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('awards', [
        'id' => $award->id,
        'title' => 'New Award Title',
    ]);
});
