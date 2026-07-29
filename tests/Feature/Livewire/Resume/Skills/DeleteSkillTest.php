<?php

use App\Livewire\Resume\Skills\DeleteSkill;
use App\Models\Skill;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing skill record successfully', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteSkill::class, ['skillId' => $skill->id])
        ->call('deleteSkill')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('skills', [
        'id' => $skill->id,
    ]);
});
