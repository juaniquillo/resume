<?php

namespace Tests\Feature;

use App\Components\Nav\ResumeNav;
use App\Components\Nav\ResumeOptionNav;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('ResumeNav items are filtered when basics are missing', function () {
    Auth::login($this->user);

    $items = ResumeNav::items();
    $names = collect($items)->pluck('name')->all();

    expect($items)->not->toBeEmpty();
    expect($names)->toContain('basics');
    // Every surviving item opted out of filtering; adding a nav item
    // must not break this, only items without the opt-out may be filtered.
    foreach ($items as $item) {
        expect($item['prevent_filter'] ?? false)->toBeTrue();
    }
});

test('ResumeNav items are not filtered when basics are present', function () {
    Basic::factory()->for($this->user)->create();
    Auth::login($this->user);

    $items = ResumeNav::items();
    $names = collect($items)->pluck('name')->all();

    expect($names)->toContain('basics');
    expect($names)->toContain('works');
    expect($names)->toContain('cover-letters');
    expect(count($items))->toBeGreaterThan(1);
});

test('ResumeOptionNav items are filtered when basics are missing', function () {
    Auth::login($this->user);

    $items = ResumeOptionNav::items();
    $names = collect($items)->pluck('name')->all();

    expect($items)->not->toBeEmpty();
    expect($names)->toContain('resume.general');
    // Filtered state must stay a strict subset of the full nav.
    expect(count($items))->toBeLessThan(3);
});

test('ResumeOptionNav items are not filtered when basics are present', function () {
    Basic::factory()->for($this->user)->create();
    Auth::login($this->user);

    $items = ResumeOptionNav::items();
    $names = collect($items)->pluck('name')->all();

    expect($names)->toContain('resume.general');
    expect($names)->toContain('resume.visibility');
    expect($names)->toContain('resume.ordering');
});
