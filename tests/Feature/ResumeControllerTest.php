<?php

use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public resume route is accessible by slug', function () {
    $user = User::factory()->create(['slug' => 'test-slug']);
    Basic::factory()->create(['user_id' => $user->id, 'name' => 'Test User']);

    $response = $this->get(route('resume', ['user' => 'test-slug']));

    $response->assertStatus(200);
    $response->assertViewIs('pages.resume');
    $response->assertSee('Test User');
});

test('resume route returns 404 for non-existent slug', function () {
    $response = $this->get('/resume/non-existent-slug');

    $response->assertStatus(404);
});
