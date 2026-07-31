<?php

use App\Livewire\Resume\Interests\InterestsTable;
use App\Models\Interest;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the interests table component', function () {
    $this->actingAs($this->user);

    Livewire::test(InterestsTable::class)
        ->assertSuccessful();
});

it('renders interests in the table when records exist', function () {
    Interest::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(InterestsTable::class)
        ->assertSuccessful();
});
