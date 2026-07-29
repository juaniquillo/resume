<?php

use App\Livewire\Resume\Skills\SkillsTable;
use App\Models\Skill;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the skills table component', function () {
    $this->actingAs($this->user);

    Livewire::test(SkillsTable::class)
        ->assertSuccessful();
});

it('renders skills in the table when records exist', function () {
    Skill::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(SkillsTable::class)
        ->assertSuccessful();
});
