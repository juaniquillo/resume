<?php

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

test('a user can have a slug', function () {
    $user = User::factory()->create(['slug' => 'test-slug']);

    expect($user->slug)->toBe('test-slug');
});

test('slug is unique', function () {
    User::factory()->create(['slug' => 'same-slug']);

    expect(fn () => User::factory()->create(['slug' => 'same-slug']))
        ->toThrow(UniqueConstraintViolationException::class);
});

test('slug cannot be null', function () {
    expect(fn () => User::factory()->create(['slug' => null]))
        ->toThrow(PDOException::class);
});

test('factory generates a slug by default', function () {
    $user = User::factory()->create();

    expect($user->slug)->not->toBeNull();
});
