<?php

use App\Livewire\Resume\Education\EducationTable;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the education table component', function () {
    $this->actingAs($this->user);

    Livewire::test(EducationTable::class)
        ->assertSuccessful();
});

it('displays education records in the table', function () {
    Education::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EducationTable::class)
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
