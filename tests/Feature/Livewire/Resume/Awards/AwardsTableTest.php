<?php

use App\Livewire\Resume\Awards\AwardsTable;
use App\Models\Award;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the awards table component', function () {
    $this->actingAs($this->user);

    Livewire::test(AwardsTable::class)
        ->assertSuccessful();
});

it('displays awards records in the table', function () {
    Award::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(AwardsTable::class)
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
