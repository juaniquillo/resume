<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class ElegantPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['elegant' => 'container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['elegant' => 'basics-container'];
    }

    public function summaryContainerThemes(): array
    {
        return ['elegant' => 'summary-container'];
    }

    public function workContainerThemes(): array
    {
        return ['elegant' => 'work-container'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['elegant' => 'volunteers-container'];
    }

    public function educationContainerThemes(): array
    {
        return ['elegant' => 'education-container'];
    }

    public function awardsContainerThemes(): array
    {
        return ['elegant' => 'awards-container'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['elegant' => 'certificates-container'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['elegant' => 'publications-container'];
    }

    public function skillsContainerThemes(): array
    {
        return ['elegant' => 'skills-container'];
    }

    public function languagesContainerThemes(): array
    {
        return ['elegant' => 'languages-container'];
    }

    public function interestsContainerThemes(): array
    {
        return ['elegant' => 'interests-container'];
    }

    public function referencesContainerThemes(): array
    {
        return ['elegant' => 'references-container'];
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
        return ['elegant' => 'name'];
    }

    public function labelThemes(): array
    {
        return ['elegant' => 'label'];
    }

    public function sectionThemes(): array
    {
        return ['elegant' => 'section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['elegant' => 'section-title'];
    }

    public function itemTitleThemes(): array
    {
        return ['elegant' => 'item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['elegant' => 'item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['elegant' => 'item-details'];
    }

    public function summaryThemes(): array
    {
        return ['elegant' => 'summary'];
    }

    public function contactContainerThemes(): array
    {
        return ['elegant' => 'contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function listThemes(): array
    {
        return ['elegant' => 'list'];
    }

    public function imageContainerThemes(): array
    {
        return ['elegant' => 'image-container'];
    }

    public function imageThemes(): array
    {
        return ['elegant' => 'image'];
    }

    public function linkThemes(): array
    {
        return ['elegant' => 'links'];
    }

    public function iconThemes(): array
    {
        return ['elegant' => 'icon'];
    }

    public function listItemThemes(): array
    {
        return ['elegant' => 'list-item'];
    }

    public function badgeThemes(): array
    {
        return ['elegant' => 'badge'];
    }

    public function keywordBadgeThemes(): array
    {
        return ['elegant' => 'badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['elegant' => 'social-badge'];
    }

    public function dateThemes(): array
    {
        return ['elegant' => 'date'];
    }

    public function subTitleThemes(): array
    {
        return ['elegant' => 'subtitle'];
    }

    public function emailThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function urlThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function locationThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function profileThemes(): array
    {
        return ['elegant' => 'contact-item'];
    }

    public function coverLetterContainerThemes(): array
    {
        return [
            'elegant' => 'cover-letter-container',
        ];
    }

    public function coverLetterThemes(): array
    {
        return [
            'elegant' => 'summary',
        ];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap',
        ];
    }

    public function localFonts(): array
    {
        return [
            ['family' => 'Playfair Display', 'path' => 'fonts/playfair-9pt-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Playfair Display', 'path' => 'fonts/playfair-9pt-bold.woff2', 'weight' => '700', 'style' => 'normal'],
            ['family' => 'Playfair Display', 'path' => 'fonts/playfair-9pt-italic.woff2', 'weight' => '400', 'style' => 'italic'],
            ['family' => 'Inter', 'path' => 'fonts/inter-18pt-light.woff2', 'weight' => '300', 'style' => 'normal'],
            ['family' => 'Inter', 'path' => 'fonts/inter-18pt-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Inter', 'path' => 'fonts/inter-18pt-medium.woff2', 'weight' => '500', 'style' => 'normal'],
            ['family' => 'Inter', 'path' => 'fonts/inter-18pt-semibold.woff2', 'weight' => '600', 'style' => 'normal'],
        ];
    }

    public function fontFamily(): string
    {
        return "'Playfair Display', serif";
    }
}



