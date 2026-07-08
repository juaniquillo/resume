<?php

use App\Livewire\Resume\Basics\UpdateBasics;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('validates required fields', function () {
    Livewire::actingAs($this->user)
        ->test(UpdateBasics::class)
        ->set('basics.name', '')
        ->set('basics.label', '')
        ->set('basics.email', '')
        ->call('updateForm')
        ->assertHasErrors(['name', 'label', 'email']);
});

it('creates a new basic record', function () {
    Livewire::actingAs($this->user)
        ->test(UpdateBasics::class)
        ->set('basics.name', 'John Doe')
        ->set('basics.label', 'Developer')
        ->set('basics.email', 'john@example.com')
        ->set('basics.url', 'https://example.com')
        ->set('basics.summary', 'Some summary')
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics'));

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
        'email' => 'old@example.com',
        'label' => 'Old Label',
        'phone' => null,
    ]);

    Livewire::actingAs($this->user)
        ->test(UpdateBasics::class)
        ->set('basics.name', 'New Name')
        ->set('basics.label', 'Developer')
        ->set('basics.email', 'john@example.com')
        ->set('basics.phone', '1234567890')
        ->set('basics.url', 'https://example.com')
        ->set('basics.summary', 'Some summary')
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics'));

    $this->assertDatabaseHas('basics', [
        'id' => $basic->id,
        'name' => 'New Name',
    ]);
});

it('handles image upload', function () {
    Storage::fake('local');

    $image = UploadedFile::fake()->image('avatar.jpg');

    Livewire::actingAs($this->user)
        ->test(UpdateBasics::class)
        ->set('basics.name', 'John Doe')
        ->set('basics.label', 'Developer')
        ->set('basics.email', 'john@example.com')
        ->set('basics.image', $image)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics'));

    $basic = $this->user->fresh()->basics;

    expect($basic->image)->not->toBeNull();
    Storage::disk('local')->assertExists($basic->image);
});

it('deletes old image when a new one is uploaded', function () {
    Storage::fake('local');

    $oldImage = UploadedFile::fake()->image('old_avatar.jpg');
    $oldPath = $oldImage->store('basics', 'local');

    Basic::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'label' => 'Old Label',
        'phone' => null,
        'image' => $oldPath,
    ]);

    Storage::disk('local')->assertExists($oldPath);

    $newImage = UploadedFile::fake()->image('new_avatar.jpg');

    Livewire::actingAs($this->user)
        ->test(UpdateBasics::class)
        ->set('basics.name', 'John Doe')
        ->set('basics.label', 'Developer')
        ->set('basics.email', 'john@example.com')
        ->set('basics.image', $newImage)
        ->call('updateForm')
        ->assertRedirect(route('dashboard.basics'));

    Storage::disk('local')->assertMissing($oldPath);
    Storage::disk('local')->assertExists($this->user->fresh()->basics->image);
});
