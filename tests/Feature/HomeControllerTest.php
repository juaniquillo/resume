<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

pest()->group('fast');

test('it renders the landing page with the static theme showcase', function () {
    $this->get(route('home'))
        ->assertStatus(200)
        ->assertSee('Craft Your Professional Story')
        ->assertSee('data-theme-showcase')
        ->assertSee('images/themes/default.png')
        ->assertSee('images/themes/elegant.png')
        ->assertSee('images/themes/bold.png')
        ->assertSee('images/themes/professional.png')
        ->assertSee('images/themes/terminal.png')
        ->assertSee('images/themes/github.png')
        ->assertSee('images/themes/default-dark.png')
        ->assertSee('images/themes/terminal-dark.png')
        ->assertSee('Retro-Modern')
        ->assertSee('Elegant Serif')
        ->assertSee('Modern & Bold')
        ->assertSee('Professional Layout')
        ->assertSee('Terminal Console')
        ->assertSee('GitHub Markdown')
        ->assertSee('data-lightbox')
        ->assertSee('data-zoom-viewport')
        ->assertSee('data-zoom-in')
        ->assertSee('data-showcase-tab');
});

test('it shows the demo link when a user exists', function () {
    User::factory()->create();

    $this->get(route('home'))
        ->assertStatus(200)
        ->assertSee('View Demo');
});
