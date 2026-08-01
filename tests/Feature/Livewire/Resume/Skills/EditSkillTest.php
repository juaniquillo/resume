<?php

use App\Enums\SkillLevel;
use App\Livewire\Resume\Skills\EditSkill;
use App\Models\Skill;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit skill component', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditSkill::class, ['skillId' => $skill->id])
        ->assertSuccessful();
});

it('updates an existing skill record successfully', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Java',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditSkill::class, ['skillId' => $skill->id])
        ->set('skills.name', 'Kotlin')
        ->set('skills.level', SkillLevel::INTERMEDIATE->value)
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('skills', [
        'id' => $skill->id,
        'name' => 'Kotlin',
        'level' => SkillLevel::INTERMEDIATE->value,
    ]);
});
