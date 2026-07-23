<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class BoldPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['bold' => 'container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['bold' => 'basics-container'];
    }

    public function summaryContainerThemes(): array
    {
        return ['bold' => 'summary-container'];
    }

    public function workContainerThemes(): array
    {
        return ['bold' => 'work-container'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['bold' => 'volunteers-container'];
    }

    public function educationContainerThemes(): array
    {
        return ['bold' => 'education-container'];
    }

    public function awardsContainerThemes(): array
    {
        return ['bold' => 'awards-container'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['bold' => 'certificates-container'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['bold' => 'publications-container'];
    }

    public function skillsContainerThemes(): array
    {
        return ['bold' => 'skills-container'];
    }

    public function languagesContainerThemes(): array
    {
        return ['bold' => 'languages-container'];
    }

    public function interestsContainerThemes(): array
    {
        return ['bold' => 'interests-container'];
    }

    public function referencesContainerThemes(): array
    {
        return ['bold' => 'references-container'];
    }

    public function projectsContainerThemes(): array
    {
        return [
            'default' => 'projects-container',
        ];
    }

    public function downloadsContainerThemes(): array
    {
        return [
            'default' => 'downloads-container',
        ];
    }

    public function nameThemes(): array
    {
        return ['bold' => 'name'];
    }

    public function labelThemes(): array
    {
        return ['bold' => 'label'];
    }

    public function sectionThemes(): array
    {
        return ['bold' => 'section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['bold' => 'section-title'];
    }

    public function itemTitleThemes(): array
    {
        return ['bold' => 'item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['bold' => 'item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['bold' => 'item-details'];
    }

    public function summaryThemes(): array
    {
        return ['bold' => 'summary'];
    }

    public function contactContainerThemes(): array
    {
        return ['bold' => 'contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function listThemes(): array
    {
        return ['bold' => 'list'];
    }

    public function imageContainerThemes(): array
    {
        return ['bold' => 'image-container'];
    }

    public function imageThemes(): array
    {
        return ['bold' => 'image'];
    }

    public function linkThemes(): array
    {
        return ['bold' => 'links'];
    }

    public function iconThemes(): array
    {
        return ['bold' => 'icon'];
    }

    public function listItemThemes(): array
    {
        return ['bold' => 'list-item'];
    }

    public function badgeThemes(): array
    {
        return ['bold' => 'badge'];
    }

    public function keywordBadgeThemes(): array
    {
        return ['bold' => 'badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['bold' => 'social-badge'];
    }

    public function dateThemes(): array
    {
        return ['bold' => 'date'];
    }

    public function subTitleThemes(): array
    {
        return ['bold' => 'subtitle'];
    }

    public function emailThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function urlThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function locationThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function profileThemes(): array
    {
        return ['bold' => 'contact-item'];
    }

    public function coverLetterContainerThemes(): array
    {
        return [
            'default' => 'prose-slate',
        ];
    }

    public function coverLetterThemes(): array
    {
        return [
            'default' => 'summary',
        ];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Syne:wght@400;700;800&display=swap',
        ];
    }

    public function localFonts(): array
    {
        return [
            ['family' => 'Montserrat', 'path' => 'fonts/montserrat-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Montserrat', 'path' => 'fonts/montserrat-bold.woff2', 'weight' => '700', 'style' => 'normal'],
            ['family' => 'Montserrat', 'path' => 'fonts/montserrat-black.woff2', 'weight' => '900', 'style' => 'normal'],
            ['family' => 'Syne', 'path' => 'fonts/syne-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Syne', 'path' => 'fonts/syne-bold.woff2', 'weight' => '700', 'style' => 'normal'],
            ['family' => 'Syne', 'path' => 'fonts/syne-extra-bold.woff2', 'weight' => '800', 'style' => 'normal'],
        ];
    }

    public function fontFamily(): string
    {
        return "'Syne', sans-serif";
    }
}
