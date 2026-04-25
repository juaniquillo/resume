<?php

use App\Models\Interest;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from interests index', function () {
    $this->get(route('dashboard.interests'))
        ->assertRedirect(route('login'));
});

it('renders the interests index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.interests'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.interests.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the interests table when records exist', function () {
    Interest::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.interests'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new interest record', function () {
    $data = [
        'name' => 'Gardening',
        'keywords' => 'plants, outdoor, green',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.interests.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('interests', [
        'user_id' => $this->user->id,
        'name' => 'Gardening',
    ]);

    $interest = Interest::where('name', 'Gardening')->first();
    expect($interest->keywords)->toBe(['plants', 'outdoor', 'green']);
});

it('validates interest data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.interests.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name']);
});

it('renders the edit interest page', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.interests.edit', $interest->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.interests.edit')
        ->assertViewHas('form');
});

it('updates an existing interest record', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Coding',
    ]);

    $data = [
        'name' => 'Programming',
        'keywords' => 'php, laravel, coding',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.interests.update', $interest->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('interests', [
        'id' => $interest->id,
        'name' => 'Programming',
    ]);

    $updatedInterest = Interest::find($interest->id);
    expect($updatedInterest->keywords)->toBe(['php', 'laravel', 'coding']);
});

it('deletes an interest record', function () {
    $interest = Interest::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.interests.destroy', $interest->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('interests', [
        'id' => $interest->id,
    ]);
});
