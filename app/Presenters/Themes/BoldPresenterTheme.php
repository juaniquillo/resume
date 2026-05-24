<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class BoldPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['bold' => 'bold-container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['bold' => 'bold-basics'];
    }

    public function summaryContainerThemes(): array
    {
        return ['bold' => 'bold-summary'];
    }

    public function workContainerThemes(): array
    {
        return ['bold' => 'bold-work'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['bold' => 'bold-volunteers'];
    }

    public function educationContainerThemes(): array
    {
        return ['bold' => 'bold-education'];
    }

    public function awardsContainerThemes(): array
    {
        return ['bold' => 'bold-awards'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['bold' => 'bold-certificates'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['bold' => 'bold-publications'];
    }

    public function skillsContainerThemes(): array
    {
        return ['bold' => 'bold-skills'];
    }

    public function languagesContainerThemes(): array
    {
        return ['bold' => 'bold-languages'];
    }

    public function interestsContainerThemes(): array
    {
        return ['bold' => 'bold-interests'];
    }

    public function referencesContainerThemes(): array
    {
        return ['bold' => 'bold-references'];
    }

    public function projectsContainerThemes(): array
    {
        return ['bold' => 'bold-projects'];
    }

    public function nameThemes(): array
    {
        return ['bold' => 'bold-name'];
    }

    public function labelThemes(): array
    {
        return ['bold' => 'bold-label'];
    }

    public function sectionThemes(): array
    {
        return ['bold' => 'bold-section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['bold' => 'bold-section-title'];
    }

    public function itemTitleThemes(): array
    {
        return ['bold' => 'bold-item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['bold' => 'bold-item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['bold' => 'bold-item-details'];
    }

    public function summaryThemes(): array
    {
        return ['bold' => 'bold-summary-text'];
    }

    public function contactContainerThemes(): array
    {
        return ['bold' => 'bold-contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function listThemes(): array
    {
        return ['bold' => 'bold-list'];
    }

    public function imageContainerThemes(): array
    {
        return ['bold' => 'bold-image-container'];
    }

    public function imageThemes(): array
    {
        return ['bold' => 'bold-image'];
    }

    public function linkThemes(): array
    {
        return ['bold' => 'bold-links'];
    }

    public function iconThemes(): array
    {
        return ['bold' => 'bold-icon'];
    }

    public function listItemThemes(): array
    {
        return ['bold' => 'bold-list-item'];
    }

    public function badgeThemes(): array
    {
        return ['bold' => 'bold-badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['bold' => 'bold-social-badge'];
    }

    public function dateThemes(): array
    {
        return ['bold' => 'bold-date'];
    }

    public function subTitleThemes(): array
    {
        return ['bold' => 'bold-subtitle'];
    }

    public function emailThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function urlThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function locationThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function profileThemes(): array
    {
        return ['bold' => 'bold-contact-item'];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Syne:wght@400;700;800&display=swap',
        ];
    }

    public function fontFamily(): string
    {
        return "'Syne', sans-serif";
    }
}
