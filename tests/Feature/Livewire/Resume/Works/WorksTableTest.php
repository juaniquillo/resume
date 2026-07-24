<?php

use App\Livewire\Resume\Works\WorksTable;
use App\Models\User;
use App\Models\Work;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the works table component', function () {
    $this->actingAs($this->user);

    Livewire::test(WorksTable::class)
        ->assertSuccessful();
});

it('displays works in the table', function () {
    Work::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(WorksTable::class)
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
