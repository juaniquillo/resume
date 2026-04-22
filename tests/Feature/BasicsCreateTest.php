<?php

use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('validates required fields', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.update'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'label', 'email']);
});

it('creates a new basic record', function () {
    $data = [
        '_token' => 'test-token',
        'name' => 'John Doe',
        'label' => 'Developer',
        'email' => 'john@example.com',
        'phone' => '1234567890',
        'url' => 'https://example.com',
        'summary' => 'Some summary',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.update'), $data)
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('basics', [
        'user_id' => $this->user->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('updates an existing basic record', function () {
    $basic = Basic::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
    ]);

    $data = [
        '_token' => 'test-token',
        'name' => 'New Name',
        'label' => 'Developer',
        'email' => 'john@example.com',
        'phone' => '1234567890',
        'url' => 'https://example.com',
        'summary' => 'Some summary',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.update'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('basics', [
        'id' => $basic->id,
        'name' => 'New Name',
    ]);
});

it('handles image upload', function () {
    Storage::fake('local');

    $image = UploadedFile::fake()->image('avatar.jpg');

    $data = [
        '_token' => 'test-token',
        'name' => 'John Doe',
        'label' => 'Developer',
        'email' => 'john@example.com',
        'phone' => '1234567890',
        'url' => 'https://example.com',
        'image' => $image,
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.update'), $data)
        ->assertRedirect();

    $basic = $this->user->fresh()->basics;

    expect($basic->image)->not->toBeNull();
    Storage::disk('local')->assertExists($basic->image);
});
