<?php

use App\Enums\EducationLevel;
use App\Models\Education;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from education index', function () {
    $this->get(route('dashboard.education'))
        ->assertRedirect(route('login'));
});

it('renders the education index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.education'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the education table when records exist', function () {
    Education::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.education'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new education record', function () {
    $data = [
        'institution' => 'University of Life',
        'url' => 'https://example.edu',
        'area' => 'Software Engineering',
        'study_type' => EducationLevel::BACHELOR_DEGREE->value,
        'score' => '4.0',
        'starts_at' => '2016-01-01',
        'ends_at' => '2020-01-01',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.education.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('education', [
        'user_id' => $this->user->id,
        'institution' => 'University of Life',
        'area' => 'Software Engineering',
        'study_type' => EducationLevel::BACHELOR_DEGREE->value,
    ]);
});

it('validates education data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.education.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['institution', 'starts_at']);
});

it('renders the edit education page', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.education.edit', $education->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.edit')
        ->assertViewHas('form');
});

it('updates an existing education record', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
        'institution' => 'Old University',
    ]);

    $data = [
        'institution' => 'New University',
        'url' => 'https://new-example.edu',
        'area' => 'Artificial Intelligence',
        'study_type' => EducationLevel::MASTER_DEGREE->value,
        'score' => '3.9',
        'starts_at' => '2020-01-01',
        'ends_at' => '2022-01-01',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.education.update', $education->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('education', [
        'id' => $education->id,
        'institution' => 'New University',
        'study_type' => EducationLevel::MASTER_DEGREE->value,
    ]);
});

it('deletes an education record', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.education.destroy', $education->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('education', [
        'id' => $education->id,
    ]);
});
