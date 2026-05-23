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
        return ['elegant' => 'basics'];
    }

    public function summaryContainerThemes(): array
    {
        return ['elegant' => 'summary-text'];
    }

    public function workContainerThemes(): array
    {
        return ['elegant' => 'work'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['elegant' => 'volunteers'];
    }

    public function educationContainerThemes(): array
    {
        return ['elegant' => 'education'];
    }

    public function awardsContainerThemes(): array
    {
        return ['elegant' => 'awards'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['elegant' => 'certificates'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['elegant' => 'publications'];
    }

    public function skillsContainerThemes(): array
    {
        return ['elegant' => 'skills'];
    }

    public function languagesContainerThemes(): array
    {
        return ['elegant' => 'languages'];
    }

    public function interestsContainerThemes(): array
    {
        return ['elegant' => 'interests'];
    }

    public function referencesContainerThemes(): array
    {
        return ['elegant' => 'references'];
    }

    public function projectsContainerThemes(): array
    {
        return ['elegant' => 'projects'];
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
        return ['elegant' => 'summary-text'];
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
        return [];
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
        return ['elegant' => 'list'];
    }

    public function badgeThemes(): array
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

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap',
        ];
    }

    public function fontFamily(): string
    {
        return "'Playfair Display', serif";
    }
}
