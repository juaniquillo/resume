<?php

use App\Livewire\Resume\PreviewIframe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

pest()->group('slow');

test('PreviewIframe component renders correctly', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PreviewIframe::class)
        ->assertSee('iframe')
        ->assertSeeHtml('x-ref="previewIframe"');
});

test('PreviewIframe component listens for the update event', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PreviewIframe::class)
        ->dispatch('resume-updated')
        ->assertStatus(200);
});
