<?php

namespace Tests\Feature;

use App\Components\Nav\ResumeNav;
use App\Components\Nav\ResumeOptionNav;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

pest()->group('fast');

test('ResumeNav items are filtered when basics are missing', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    $items = ResumeNav::items();
    
    expect($items)->toHaveCount(1);
    expect($items[0]['name'])->toBe('basics');
});

test('ResumeNav items are not filtered when basics are present', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    
    $this->actingAs($user);
    
    $items = ResumeNav::items();
    
    expect(count($items))->toBeGreaterThan(1);
});

test('ResumeOptionNav items are filtered when basics are missing', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    $items = ResumeOptionNav::items();
    
    expect($items)->toHaveCount(1);
    expect($items[0]['name'])->toBe('resume.general');
});

test('ResumeOptionNav items are not filtered when basics are present', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();
    
    $this->actingAs($user);
    
    $items = ResumeOptionNav::items();
    
    expect(count($items))->toBeGreaterThan(1);
});
