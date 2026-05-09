<?php

use App\Models\Education;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from education courses index', function () {
    $this->get(route('dashboard.education.courses', $this->education->id))
        ->assertRedirect(route('login'));
});

it('renders the education courses index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.education.courses', $this->education->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.courses.index')
        ->assertViewHas('form');
});

it('stores a new education course', function () {
    $data = [
        'course' => 'Advanced Laravel Development',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.education.courses.store', $this->education->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('courses', [
        'courseable_id' => $this->education->id,
        'courseable_type' => Education::class,
        'course' => 'Advanced Laravel Development',
    ]);
});

it('renders the edit education course page', function () {
    $course = $this->education->courses()->create([
        'course' => 'Old course',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.education.courses.edit', [$this->education->id, $course->id]))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.courses.edit')
        ->assertViewHas('form');
});

it('updates an existing education course', function () {
    $course = $this->education->courses()->create([
        'course' => 'Old course',
    ]);

    $data = [
        'course' => 'New course name',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.education.courses.update', [$this->education->id, $course->id]), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'course' => 'New course name',
    ]);
});

it('deletes an education course', function () {
    $course = $this->education->courses()->create([
        'course' => 'To be deleted',
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.education.courses.destroy', [$this->education->id, $course->id]), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});
