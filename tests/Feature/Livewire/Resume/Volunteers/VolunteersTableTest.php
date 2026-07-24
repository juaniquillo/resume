<?php

use App\Livewire\Resume\Volunteers\VolunteersTable;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the volunteers table component', function () {
    $this->actingAs($this->user);

    Livewire::test(VolunteersTable::class)
        ->assertSuccessful();
});

it('displays volunteers in the table', function () {
    Volunteer::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(VolunteersTable::class)
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
