<?php

use App\Enums\SkillLevel;
use App\Models\Skill;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from skills index', function () {
    $this->get(route('dashboard.skills'))
        ->assertRedirect(route('login'));
});

it('renders the skills index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.skills'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.skills.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null)
        ->assertSee('list="skill_level_data"', false)
        ->assertSee('<datalist id="skill_level_data">', false)
        ->assertSee('<option value="Expert">', false);
});

it('renders the skills table when records exist', function () {
    Skill::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.skills'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new skill record', function () {
    $data = [
        'name' => 'PHP',
        'level' => SkillLevel::EXPERT->value,
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.skills.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('skills', [
        'user_id' => $this->user->id,
        'name' => 'PHP',
        'level' => SkillLevel::EXPERT->value,
    ]);
});

it('stores a skill with a custom level', function () {
    $data = [
        'name' => 'Cooking',
        'level' => 'Master Chef',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.skills.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('skills', [
        'user_id' => $this->user->id,
        'name' => 'Cooking',
        'level' => 'Master Chef',
    ]);
});

it('validates skill data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.skills.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'level']);
});

it('renders the edit skill page', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.skills.edit', $skill->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.skills.edit')
        ->assertViewHas('form')
        ->assertSee('list="skill_level_data"', false)
        ->assertSee('<datalist id="skill_level_data">', false)
        ->assertSee('<option value="Expert">', false);
});

it('updates an existing skill record', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Java',
    ]);

    $data = [
        'name' => 'Kotlin',
        'level' => SkillLevel::INTERMEDIATE->value,
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.skills.update', $skill->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('skills', [
        'id' => $skill->id,
        'name' => 'Kotlin',
        'level' => SkillLevel::INTERMEDIATE->value,
    ]);
});

it('deletes a skill record', function () {
    $skill = Skill::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.skills.destroy', $skill->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('skills', [
        'id' => $skill->id,
    ]);
});
