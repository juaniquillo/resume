<?php

namespace Tests\Feature\Livewire\Options;

use App\Livewire\Options\ToggleDraftState;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('ToggleDraftState component toggles the draft status correctly', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update([
        'slug' => 'test-user',
        'is_draft' => true,
    ]);
    
    // Also create basics so Helpers::isResumeInDraftState doesn't return true because of missing basics
    Basic::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ToggleDraftState::class)
        ->assertSee(__('Draft'))
        ->call('toggle')
        ->assertSee(__('Published'))
        ->assertDispatched('resume-updated')
        ->assertDispatched('toast');

    expect($user->fresh()->generalOptions->is_draft)->toBeFalse();

    Livewire::actingAs($user)
        ->test(ToggleDraftState::class)
        ->call('toggle')
        ->assertSee(__('Draft'));

    expect($user->fresh()->generalOptions->is_draft)->toBeTrue();
});

test('ToggleDraftState component renders nothing if slug is missing', function () {
    $user = User::factory()->create();
    $user->generalOptions()->delete();
    
    Livewire::actingAs($user)
        ->test(ToggleDraftState::class)
        ->assertDontSee(__('Draft'))
        ->assertDontSee(__('Published'));
});
