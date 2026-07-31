<?php

use App\Livewire\Resume\Interests\EditInterest;
use App\Models\Interest;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit interest component', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditInterest::class, ['interestId' => $interest->id])
        ->assertSuccessful();
});

it('updates an existing interest record successfully', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Reading',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditInterest::class, ['interestId' => $interest->id])
        ->set('interests.name', 'Writing')
        ->set('interests.keywords', ['Technical', 'Blogging'])
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('interests', [
        'id' => $interest->id,
        'name' => 'Writing',
    ]);
});
