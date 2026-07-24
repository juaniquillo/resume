<?php

use App\Livewire\Resume\OgImageReset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake();
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the og image management page', function () {
    $this->get(route('dashboard.resume.og'))
        ->assertOk()
        ->assertSee('resume.og-image-reset');
});

it('regenerates the og image via livewire', function () {
    $path = "og-images/ogg-{$this->user->id}.png";
    Storage::put($path, 'dummy content');

    expect(Storage::exists($path))->toBeTrue();

    Livewire::test(OgImageReset::class, ['test' => true])
        ->call('regenerate')
        ->assertDispatched('notify');

    expect(Storage::exists($path))->toBeFalse();
});
