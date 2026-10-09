<?php

use App\Enums\ResumeTheme;
use Tests\TestCase;

uses(TestCase::class);

test('showcase features the six marketing themes', function () {
    expect(ResumeTheme::showcase())->toBe([
        ResumeTheme::DEFAULT,
        ResumeTheme::ELEGANT,
        ResumeTheme::BOLD,
        ResumeTheme::PROFESSIONAL,
        ResumeTheme::TERMINAL,
        ResumeTheme::GITHUB,
    ]);
});

test('showcase excludes the blank and pdf themes', function () {
    expect(ResumeTheme::showcase())
        ->not->toContain(ResumeTheme::BLANK)
        ->not->toContain(ResumeTheme::PDF);
});

test('only blank and pdf themes are hidden from the showcase', function () {
    expect(ResumeTheme::BLANK->isHiddenFromShowcase())->toBeTrue();
    expect(ResumeTheme::PDF->isHiddenFromShowcase())->toBeTrue();

    foreach (ResumeTheme::showcase() as $theme) {
        expect($theme->isHiddenFromShowcase())->toBeFalse();
    }
});

test('showcase contains every visible theme', function () {
    $visible = array_values(
        array_filter(ResumeTheme::cases(), fn (ResumeTheme $theme) => ! $theme->isHiddenFromShowcase())
    );

    expect(ResumeTheme::showcase())->toBe($visible);
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
});

test('showcase data exposes key, label and tab for every theme', function () {
    $data = ResumeTheme::showcaseData();

    expect($data)->toHaveCount(6);
    expect($data[0])->toBe([
        'key' => 'default',
        'label' => 'Retro-Modern (Space Mono)',
        'tab' => 'Default',
    ]);

    foreach ($data as $item) {
        expect($item['key'])->toBeString()->not->toBeEmpty();
        expect($item['label'])->toBeString()->not->toBeEmpty();
        expect($item['tab'])->toBeString()->not->toBeEmpty();
    }
});

test('showcase tabs use the short labels', function () {
    $tabs = collect(ResumeTheme::showcaseData())->pluck('tab', 'key')->all();

    expect($tabs['elegant'])->toBe('Elegant');
    expect($tabs['github'])->toBe('GitHub');
});
