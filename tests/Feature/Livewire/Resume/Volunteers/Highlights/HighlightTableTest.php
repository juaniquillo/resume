<?php

use App\Livewire\Resume\Highlights\HighlightTable;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->volunteer = Volunteer::factory()->create(['user_id' => $this->user->id]);
});

it('renders the volunteer highlights table component', function () {
    $this->actingAs($this->user);

    Livewire::test(HighlightTable::class, ['model' => $this->volunteer])
        ->assertSuccessful();
});

it('displays volunteer highlights in the table', function () {
    $this->volunteer->highlights()->create([
        'highlight' => 'Test highlight',
    ]);

    $this->actingAs($this->user);

    Livewire::test(HighlightTable::class, ['model' => $this->volunteer])
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
