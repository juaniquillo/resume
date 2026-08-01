<?php

use App\Livewire\Resume\Languages\LanguagesTable;
use App\Models\Language;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the languages table component', function () {
    $this->actingAs($this->user);

    Livewire::test(LanguagesTable::class)
        ->assertSuccessful();
});

it('renders languages in the table when records exist', function () {
    Language::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(LanguagesTable::class)
        ->assertSuccessful();
});
