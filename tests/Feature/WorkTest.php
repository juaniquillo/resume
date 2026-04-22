<?php

use App\Models\User;
use App\Models\Work;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from works index', function () {
    $this->get(route('dashboard.works'))
        ->assertRedirect(route('login'));
});

it('renders the works index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.works'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.works.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the works table when records exist', function () {
    Work::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.works'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new work record', function () {
    $data = [
        'name' => 'Acme Corp',
        'position' => 'Developer',
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
        'summary' => 'Doing things',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.works.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('works', [
        'user_id' => $this->user->id,
        'name' => 'Acme Corp',
        'position' => 'Developer',
    ]);
});

it('validates work data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.works.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'position', 'starts_at']);
});

it('renders the edit work page', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.works.edit', $work->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.works.edit')
        ->assertViewHas('form');
});

it('updates an existing work record', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Corp',
    ]);

    $data = [
        'name' => 'New Corp',
        'position' => 'Senior Developer',
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
        'summary' => 'Updated summary',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.works.update', $work->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('works', [
        'id' => $work->id,
        'name' => 'New Corp',
    ]);
});

it('deletes a work record', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.works.destroy', $work->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('works', [
        'id' => $work->id,
    ]);
});
