<?php

use App\Livewire\Resume\References\ReferencesTable;
use App\Models\Reference;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the references table component', function () {
    $this->actingAs($this->user);

    Livewire::test(ReferencesTable::class)
        ->assertSuccessful();
});

it('renders references in the table when records exist', function () {
    Reference::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(ReferencesTable::class)
        ->assertSuccessful();
});
