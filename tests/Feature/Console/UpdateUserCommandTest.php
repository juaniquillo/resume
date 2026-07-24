<?php

use App\Enums\SlugBlacklist;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

test('it updates user name and slug successfully', function () {
    $user = User::factory()->create();

    Artisan::call('user:update', [
        'id' => $user->id,
        '--name' => 'New Name',
        '--slug' => 'new-slug',
    ]);

    expect($user->fresh()->name)->toBe('New Name');
    expect($user->fresh()->generalOptions->slug)->toBe('new-slug');
});

test('it rejects blacklisted slug', function () {
    $user = User::factory()->create();
    $slug = SlugBlacklist::ADMIN->value;

    Artisan::call('user:update', [
        'id' => $user->id,
        '--slug' => $slug,
    ]);

    expect($user->fresh()->generalOptions?->slug)->not->toBe($slug);
});

test('it rejects taken slug', function () {
    $user1 = User::factory()->create();
    $user1->generalOptions()->update(['slug' => 'taken-slug']);

    $user2 = User::factory()->create();

    Artisan::call('user:update', [
        'id' => $user2->id,
        '--slug' => 'taken-slug',
    ]);

    expect($user2->fresh()->generalOptions?->slug)->not->toBe('taken-slug');
});
