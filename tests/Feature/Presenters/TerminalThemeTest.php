<?php

use App\Enums\ResumeTheme;
use App\Models\Basic;
use App\Models\User;
use App\Presenters\ResumePresenter;
use App\Presenters\Themes\TerminalPresenterTheme;
use App\Presenters\Themes\ThemeFactory;

pest()->group('fast');

test('terminal theme can be instantiated', function () {
    $theme = ThemeFactory::make('terminal');
    expect($theme)->toBeInstanceOf(TerminalPresenterTheme::class);

    $enumTheme = ResumeTheme::TERMINAL->instance();
    expect($enumTheme)->toBeInstanceOf(TerminalPresenterTheme::class);
});

test('terminal theme returns correct keys', function () {
    $theme = new TerminalPresenterTheme;
    expect($theme->containerThemes())->toHaveKey('terminal');
    expect($theme->containerThemes()['terminal'])->toBe('container');
    expect($theme->nameThemes()['terminal'])->toBe('name');
});

test('resume presenter can render with terminal theme', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create([
        'name' => 'John Hacker',
        'label' => 'Security Analyst',
    ]);

    $presenter = new ResumePresenter($user, new TerminalPresenterTheme);
    $html = (string) $presenter->present()->toHtml();

    expect($html)->toContain('John Hacker');
    expect($html)->toContain('mx-4');
    expect($html)->toContain('text-4xl');
});



