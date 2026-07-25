<?php

use App\Models\Education;
use App\Models\User;

pest()->group('fast');

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
        ->assertViewIs('dashboard.education.index');
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
