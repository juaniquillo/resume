<?php

use App\Support\ResumeLimit;
use Tests\TestCase;

uses(TestCase::class);

test('resume limit constants have correct values', function () {
    expect(ResumeLimit::WORK)->toBe(15);
    expect(ResumeLimit::EDUCATION)->toBe(10);
    expect(ResumeLimit::VOLUNTEERS)->toBe(10);
    expect(ResumeLimit::AWARDS)->toBe(10);
    expect(ResumeLimit::CERTIFICATES)->toBe(15);
    expect(ResumeLimit::PUBLICATIONS)->toBe(15);
    expect(ResumeLimit::SKILLS)->toBe(30);
    expect(ResumeLimit::LANGUAGES)->toBe(10);
    expect(ResumeLimit::INTERESTS)->toBe(10);
    expect(ResumeLimit::REFERENCES)->toBe(10);
    expect(ResumeLimit::PROJECTS)->toBe(15);
    expect(ResumeLimit::PROFILES)->toBe(10);
    expect(ResumeLimit::HIGHLIGHTS)->toBe(10);
    expect(ResumeLimit::COURSES)->toBe(10);
    expect(ResumeLimit::IMPORTS)->toBe(10);
    expect(ResumeLimit::EXPORTS)->toBe(10);
});

test('resume limit error message helper formats correctly', function () {
    $message = ResumeLimit::errorMessage('work experiences', ResumeLimit::WORK);

    expect($message)->toContain('15');
    expect($message)->toContain('work experiences');
});
