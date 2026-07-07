<?php

use App\Models\User;
use App\Models\Work;

pest()->group('fast');

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
