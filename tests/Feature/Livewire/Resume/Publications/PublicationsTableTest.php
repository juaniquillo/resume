<?php

use App\Livewire\Resume\Publications\PublicationsTable;
use App\Models\Publication;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the publications table component', function () {
    $this->actingAs($this->user);

    Livewire::test(PublicationsTable::class)
        ->assertSuccessful();
});

it('renders publications in the table when records exist', function () {
    Publication::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(PublicationsTable::class)
        ->assertSuccessful();
});
