<?php

namespace App\Enums;

enum ResumeSection: string
{
    case SUMMARY = 'summary';
    case WORK = 'work';
    case VOLUNTEERS = 'volunteers';
    case EDUCATION = 'education';
    case AWARDS = 'awards';
    case CERTIFICATES = 'certificates';
    case PUBLICATIONS = 'publications';
    case SKILLS = 'skills';
    case LANGUAGES = 'languages';
    case INTERESTS = 'interests';
    case REFERENCES = 'references';
    case PROJECTS = 'projects';
    case DOWNLOADS = 'downloads';

    public function label(): string
    {
        return match ($this) {
            self::SUMMARY => 'Summary',
            self::WORK => 'Experience',
            self::VOLUNTEERS => 'Volunteering',
            self::EDUCATION => 'Education',
            self::AWARDS => 'Awards',
            self::CERTIFICATES => 'Certificates',
            self::PUBLICATIONS => 'Publications',
            self::SKILLS => 'Skills',
            self::LANGUAGES => 'Languages',
            self::INTERESTS => 'Interests',
            self::REFERENCES => 'References',
            self::PROJECTS => 'Projects',
            self::DOWNLOADS => 'Downloads',
        };
    }

    /** @return array<string, string> */
    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }

        return $labels;
    }

    /**
     * Section keys accepted by the API ?section= query parameter, in export order.
     *
     * Display sections with no top-level export key are excluded: downloads is
     * never exported, and summary nests inside basics. Meta and basics are valid
     * API keys but are not display sections, so they lead the list.
     *
     * @return list<string>
     */
    public static function apiSections(): array
    {
        $sections = ['meta', 'basics'];

        foreach (self::cases() as $case) {
            if ($case === self::DOWNLOADS || $case === self::SUMMARY) {
                continue;
            }

            $sections[] = $case->value;
        }

        return $sections;
    }
}
