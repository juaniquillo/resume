<?php

namespace Tests\Feature;

use App\Enums\ResumeTheme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

pest()->group('fast');

test('it renders the landing page with the theme showcase', function () {
    $response = $this->get(route('home'))
        ->assertStatus(200)
        ->assertSee('Craft Your Professional Story')
        ->assertSee('data-theme-showcase')
        ->assertSee('data-lightbox')
        ->assertSee('data-zoom-viewport')
        ->assertSee('data-zoom-in')
        ->assertSee('data-showcase-tab');

    foreach (ResumeTheme::showcaseData() as $theme) {
        $response
            ->assertSee('images/themes/'.$theme['key'].'.png')
            ->assertSee('images/themes/'.$theme['key'].'-dark.png')
            ->assertSee($theme['label']);
    }

    foreach (ResumeTheme::cases() as $theme) {
        if ($theme->isHiddenFromShowcase()) {
            $response->assertDontSee('images/themes/'.$theme->value.'.png');
        }
    }
});

test('it shows the demo link when a user exists', function () {
    User::factory()->create();

    $this->get(route('home'))
        ->assertStatus(200)
        ->assertSee('View Demo');
});
