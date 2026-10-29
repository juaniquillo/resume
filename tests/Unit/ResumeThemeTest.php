<?php

use App\Enums\ResumeTheme;
use Tests\TestCase;

uses(TestCase::class);

test('showcase contains every visible theme and excludes hidden ones', function () {
    $visible = array_values(
        array_filter(ResumeTheme::cases(), fn (ResumeTheme $theme) => ! $theme->isHiddenFromShowcase())
    );

    expect(ResumeTheme::showcase())->toBe($visible);
    expect(ResumeTheme::showcase())->not->toBeEmpty();

    foreach (ResumeTheme::cases() as $theme) {
        if ($theme->isHiddenFromShowcase()) {
            expect(ResumeTheme::showcase())->not->toContain($theme);
        } else {
            expect(ResumeTheme::showcase())->toContain($theme);
        }
    }
});

test('blank and pdf themes are always hidden from the showcase', function () {
    expect(ResumeTheme::BLANK->isHiddenFromShowcase())->toBeTrue();
    expect(ResumeTheme::PDF->isHiddenFromShowcase())->toBeTrue();
});

test('every showcased theme has a non-empty label', function () {
    foreach (ResumeTheme::showcase() as $theme) {
        expect($theme->label())->toBeString()->not->toBeEmpty();
    }
});

test('short labels stay concise for every theme', function () {
    expect(ResumeTheme::DEFAULT->shortLabel())->toBe('Default');
    expect(ResumeTheme::ELEGANT->shortLabel())->toBe('Elegant');
    expect(ResumeTheme::BLANK->shortLabel())->toBe('Blank');
    expect(ResumeTheme::BOLD->shortLabel())->toBe('Bold');
    expect(ResumeTheme::PDF->shortLabel())->toBe('PDF');
    expect(ResumeTheme::PROFESSIONAL->shortLabel())->toBe('Professional');
    expect(ResumeTheme::TERMINAL->shortLabel())->toBe('Terminal');
    expect(ResumeTheme::GITHUB->shortLabel())->toBe('GitHub');
    expect(ResumeTheme::MODERN->shortLabel())->toBe('Retro-Pro');
});

test('showcase data exposes key, label and tab for every showcased theme', function () {
    $data = ResumeTheme::showcaseData();
    $showcase = ResumeTheme::showcase();

    expect($data)->toHaveCount(count($showcase));

    foreach ($data as $index => $item) {
        $theme = $showcase[$index];

        expect($item['key'])->toBe($theme->value);
        expect($item['label'])->toBe($theme->label());
        expect($item['tab'])->toBe($theme->shortLabel());
    }
});

test('showcase tabs use the short labels', function () {
    $tabs = collect(ResumeTheme::showcaseData())->pluck('tab', 'key')->all();

    foreach (ResumeTheme::showcase() as $theme) {
        expect($tabs[$theme->value])->toBe($theme->shortLabel());
    }
});

test('every showcased theme has light and dark screenshots', function () {
    foreach (ResumeTheme::showcase() as $theme) {
        $light = public_path('images/themes/'.$theme->value.'.png');
        $dark = public_path('images/themes/'.$theme->value.'-dark.png');

        expect(is_file($light))->toBeTrue("Missing screenshot: {$light}");
        expect(is_file($dark))->toBeTrue("Missing screenshot: {$dark}");
        expect(filesize($light))->toBeGreaterThan(0);
        expect(filesize($dark))->toBeGreaterThan(0);
    }
});
