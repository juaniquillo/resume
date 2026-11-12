<?php

use App\Enums\ResumeSection;
use Tests\TestCase;

uses(TestCase::class);

test('api sections match the export keys in order', function () {
    expect(ResumeSection::apiSections())->toBe([
        'meta',
        'basics',
        'work',
        'volunteers',
        'education',
        'awards',
        'certificates',
        'publications',
        'skills',
        'languages',
        'interests',
        'references',
        'projects',
    ]);
});

test('api sections exclude non exported display sections', function () {
    $sections = ResumeSection::apiSections();

    expect($sections)->not->toContain('downloads', 'summary');
});
