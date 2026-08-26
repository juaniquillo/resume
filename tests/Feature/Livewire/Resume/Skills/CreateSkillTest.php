<?php

use App\Enums\SkillLevel;
use App\Livewire\Resume\Skills\CreateSkill;
use App\Models\Skill;
use App\Models\User;
use App\Support\ResumeLimit;
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

it('skills records have a limit', function () {
    $this->actingAs($this->user);
    Skill::factory()->count(ResumeLimit::SKILLS)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateSkill::class)
        ->set('skills.name', 'Extra Skill')
        ->set('skills.level', SkillLevel::BEGINNER->value)
        ->call('createForm');

    $this->assertDatabaseCount('skills', ResumeLimit::SKILLS);
});
