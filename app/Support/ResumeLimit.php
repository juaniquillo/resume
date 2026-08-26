<?php

namespace App\Support;

class ResumeLimit
{
    public const WORK = 15;

    public const EDUCATION = 10;

    public const VOLUNTEERS = 10;

    public const AWARDS = 10;

    public const CERTIFICATES = 15;

    public const PUBLICATIONS = 15;

    public const SKILLS = 30;

    public const LANGUAGES = 10;

    public const INTERESTS = 10;

    public const REFERENCES = 10;

    public const PROJECTS = 15;

    public const PROFILES = 10;

    public const HIGHLIGHTS = 10;

    public const COURSES = 10;

    public const IMPORTS = 10;

    public const EXPORTS = 10;

    public static function errorMessage(string $sectionName, int $limit): string
    {
        return __('You can only have up to :limit :section items. Please delete an old one first.', [
            'limit' => $limit,
            'section' => $sectionName,
        ]);
    }
}
