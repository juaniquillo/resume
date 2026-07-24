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

    expect($items)->toHaveCount(1);
    expect($items[0]['name'])->toBe('basics');
});

test('ResumeNav items are not filtered when basics are present', function () {
    Basic::factory()->for($this->user)->create();
    Auth::login($this->user);

    $items = ResumeNav::items();

    expect(count($items))->toBeGreaterThan(1);
});

test('ResumeOptionNav items are filtered when basics are missing', function () {
    Auth::login($this->user);

    $items = ResumeOptionNav::items();

    expect($items)->toHaveCount(1);
    expect($items[0]['name'])->toBe('resume.general');
});

test('ResumeOptionNav items are not filtered when basics are present', function () {
    Basic::factory()->for($this->user)->create();
    Auth::login($this->user);

    $items = ResumeOptionNav::items();

    expect(count($items))->toBeGreaterThan(1);
});



