<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class DefaultPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return [
            'default' => 'container',
        ];
    }

    public function basicsContainerThemes(): array
    {
        return [
            'default' => 'basics-container',
        ];
    }

    public function summaryContainerThemes(): array
    {
        return [
            'default' => 'summary-container',
        ];
    }

    public function workContainerThemes(): array
    {
        return [
            'default' => 'work-container',
        ];
    }

    public function volunteersContainerThemes(): array
    {
        return [
            'default' => 'volunteers-container',
        ];
    }

    public function educationContainerThemes(): array
    {
        return [
            'default' => 'education-container',
        ];
    }

    public function awardsContainerThemes(): array
    {
        return [
            'default' => 'awards-container',
        ];
    }

    public function certificatesContainerThemes(): array
    {
        return [
            'default' => 'certificates-container',
        ];
    }

    public function publicationsContainerThemes(): array
    {
        return [
            'default' => 'publications-container',
        ];
    }

    public function skillsContainerThemes(): array
    {
        return [
            'default' => 'skills-container',
        ];
    }

    public function languagesContainerThemes(): array
    {
        return [
            'default' => 'languages-container',
        ];
    }

    public function interestsContainerThemes(): array
    {
        return [
            'default' => 'interests-container',
        ];
    }

    public function referencesContainerThemes(): array
    {
        return [
            'default' => 'references-container',
        ];
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
        return [
            'default' => 'name',
        ];
    }

    public function labelThemes(): array
    {
        return [
            'default' => 'label',
        ];
    }

    public function sectionThemes(): array
    {
        return [
            'default' => 'section',
        ];
    }

    public function sectionTitleThemes(): array
    {
        return [
            'default' => 'section-title',
        ];
    }

    public function itemTitleThemes(): array
    {
        return [
            'default' => 'item-title',
        ];
    }

    public function itemContainerThemes(): array
    {
        return [
            'default' => 'item-container',
        ];
    }

    public function itemDetailsThemes(): array
    {
        return [
            'default' => 'item-details',
        ];
    }

    public function summaryThemes(): array
    {
        return [
            'default' => 'summary',
        ];
    }

    public function contactContainerThemes(): array
    {
        return [
            'default' => 'contact-container',
        ];
    }

    public function contactItemThemes(): array
    {
        return [
            'default' => 'contact-item',
        ];
    }

    public function listThemes(): array
    {
        return [
            'default' => 'list',
        ];
    }

    public function imageContainerThemes(): array
    {
        return [];
    }

    public function imageThemes(): array
    {
        return [
            'default' => 'image',
        ];
    }

    public function linkThemes(): array
    {
        return [
            'default' => 'links',
        ];
    }

    public function iconThemes(): array
    {
        return [
            'default' => 'icon',
        ];
    }

    public function listItemThemes(): array
    {
        return [
            'default' => 'list-item',
        ];
    }

    public function badgeThemes(): array
    {
        return [
            'default' => 'badge',
        ];
    }

    public function socialBadgeThemes(): array
    {
        return [
            'default' => 'social-badge',
        ];
    }

    public function dateThemes(): array
    {
        return [
            'default' => 'date',
        ];
    }

    public function subTitleThemes(): array
    {
        return [
            'default' => 'subtitle',
        ];
    }

    public function emailThemes(): array
    {
        return [
            'default' => 'contact-item',
        ];
    }

    public function phoneThemes(): array
    {
        return [
            'default' => 'contact-item',
        ];
    }

    public function urlThemes(): array
    {
        return [
            'default' => 'contact-item',
        ];
    }

    public function locationThemes(): array
    {
        return [
            'default' => 'badge',
        ];
    }

    public function profileThemes(): array
    {
        return [
            'default' => 'contact-item',
        ];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap',
        ];
    }

    public function fontFamily(): string
    {
        return "'Space Mono', monospace";
    }
}
