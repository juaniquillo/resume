<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class TerminalPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['terminal' => 'container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['terminal' => 'basics-container'];
    }

    public function summaryContainerThemes(): array
    {
        return ['terminal' => 'summary-container'];
    }

    public function workContainerThemes(): array
    {
        return ['terminal' => 'work-container'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['terminal' => 'volunteers-container'];
    }

    public function educationContainerThemes(): array
    {
        return ['terminal' => 'education-container'];
    }

    public function awardsContainerThemes(): array
    {
        return ['terminal' => 'awards-container'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['terminal' => 'certificates-container'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['terminal' => 'publications-container'];
    }

    public function skillsContainerThemes(): array
    {
        return ['terminal' => 'skills-container'];
    }

    public function languagesContainerThemes(): array
    {
        return ['terminal' => 'languages-container'];
    }

    public function interestsContainerThemes(): array
    {
        return ['terminal' => 'interests-container'];
    }

    public function referencesContainerThemes(): array
    {
        return ['terminal' => 'references-container'];
    }

    public function projectsContainerThemes(): array
    {
        return ['terminal' => 'projects-container'];
    }

    public function downloadsContainerThemes(): array
    {
        return ['terminal' => 'downloads-container'];
    }

    public function nameThemes(): array
    {
        return ['terminal' => 'name'];
    }

    public function labelThemes(): array
    {
        return ['terminal' => 'label'];
    }

    public function sectionThemes(): array
    {
        return ['terminal' => 'section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['terminal' => 'section-title'];
    }

    public function itemTitleThemes(): array
    {
        return ['terminal' => 'item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['terminal' => 'item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['terminal' => 'item-details'];
    }

    public function summaryThemes(): array
    {
        return ['terminal' => 'summary'];
    }

    public function contactContainerThemes(): array
    {
        return ['terminal' => 'contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['terminal' => 'contact-item'];
    }

    public function listThemes(): array
    {
        return ['terminal' => 'list'];
    }

    public function imageContainerThemes(): array
    {
        return ['terminal' => 'image-container'];
    }

    public function imageThemes(): array
    {
        return ['terminal' => 'image'];
    }

    public function linkThemes(): array
    {
        return ['terminal' => 'links'];
    }

    public function iconThemes(): array
    {
        return ['terminal' => 'icon'];
    }

    public function listItemThemes(): array
    {
        return ['terminal' => 'list-item'];
    }

    public function badgeThemes(): array
    {
        return ['terminal' => 'badge'];
    }

    public function keywordBadgeThemes(): array
    {
        return ['terminal' => 'keyword-badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['terminal' => 'social-badge'];
    }

    public function dateThemes(): array
    {
        return ['terminal' => 'date'];
    }

    public function subTitleThemes(): array
    {
        return ['terminal' => 'subtitle'];
    }

    public function emailThemes(): array
    {
        return ['terminal' => 'email-item'];
    }

    public function phoneThemes(): array
    {
        return ['terminal' => 'phone-item'];
    }

    public function urlThemes(): array
    {
        return ['terminal' => 'url-item'];
    }

    public function locationThemes(): array
    {
        return ['terminal' => 'location-item'];
    }

    public function profileThemes(): array
    {
        return ['terminal' => 'contact-item'];
    }

    public function coverLetterContainerThemes(): array
    {
        return [
            'terminal' => 'section',
        ];
    }

    public function coverLetterThemes(): array
    {
        return [
            'terminal' => 'summary',
        ];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap',
        ];
    }

    public function localFonts(): array
    {
        return [
            ['family' => 'IBM Plex Mono', 'path' => 'fonts/space-mono-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'IBM Plex Mono', 'path' => 'fonts/space-mono-bold.woff2', 'weight' => '700', 'style' => 'normal'],
            ['family' => 'IBM Plex Mono', 'path' => 'fonts/space-mono-italic.woff2', 'weight' => '400', 'style' => 'italic'],
            ['family' => 'IBM Plex Mono', 'path' => 'fonts/space-mono-bold-italic.woff2', 'weight' => '700', 'style' => 'italic'],
        ];
    }

    public function fontFamily(): string
    {
        return "'IBM Plex Mono', monospace";
    }
}
