<?php

use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('resume og show route displays image when hide_image is false', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['hide_image' => false]);
    $basic = Basic::factory()->create(['user_id' => $user->id, 'image' => 'avatar.jpg']);

    $response = $this->get(route('resume.og.show', $user));

    $response->assertOk();
    $response->assertSee(route('image.serve', $basic->uuid));
});

test('resume og show route hides image when hide_image is true', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $user->generalOptions()->update(['hide_image' => true]);
    $basic = Basic::factory()->create(['user_id' => $user->id, 'image' => 'avatar.jpg']);

    $response = $this->get(route('resume.og.show', $user));

    $response->assertOk();
    $response->assertDontSee(route('image.serve', $basic->uuid));
    $response->assertSee('J'); // Initial letter fallback for John Doe
});
