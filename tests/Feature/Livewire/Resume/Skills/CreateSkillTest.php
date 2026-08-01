<?php

use App\Enums\SkillLevel;
use App\Livewire\Resume\Skills\CreateSkill;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create skill component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateSkill::class)
        ->assertSuccessful();
});

it('creates a new skill record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateSkill::class)
        ->set('skills.name', 'PHP')
        ->set('skills.level', SkillLevel::EXPERT->value)
        ->set('skills.keywords', ['Laravel', 'Livewire'])
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('skills', [
        'user_id' => $this->user->id,
        'name' => 'PHP',
        'level' => SkillLevel::EXPERT->value,
    ]);
});
