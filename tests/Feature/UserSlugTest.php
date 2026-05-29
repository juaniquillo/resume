<?php

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

pest()->group('fast');

test('a user can have a slug', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'test-slug']);

    expect($user->generalOptions->slug)->toBe('test-slug');
});

test('slug is unique', function () {
    $user1 = User::factory()->create();
    $user1->generalOptions()->update(['slug' => 'same-slug']);

    $user2 = User::factory()->create();

    expect(fn () => $user2->generalOptions()->update(['slug' => 'same-slug']))
        ->toThrow(UniqueConstraintViolationException::class);
});

test('factory generates a slug by default', function () {
    $user = User::factory()->create();

    expect($user->generalOptions->slug)->not->toBeNull();
});
