<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class PdfPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['pdf' => 'pdf-container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['pdf' => 'pdf-basics'];
    }

    public function summaryContainerThemes(): array
    {
        return ['pdf' => 'pdf-summary'];
    }

    public function workContainerThemes(): array
    {
        return ['pdf' => 'pdf-work'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['pdf' => 'pdf-volunteers'];
    }

    public function educationContainerThemes(): array
    {
        return ['pdf' => 'pdf-education'];
    }

    public function awardsContainerThemes(): array
    {
        return ['pdf' => 'pdf-awards'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['pdf' => 'pdf-certificates'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['pdf' => 'pdf-publications'];
    }

    public function skillsContainerThemes(): array
    {
        return ['pdf' => 'pdf-skills'];
    }

    public function languagesContainerThemes(): array
    {
        return ['pdf' => 'pdf-languages'];
    }

    public function interestsContainerThemes(): array
    {
        return ['pdf' => 'pdf-interests'];
    }

    public function referencesContainerThemes(): array
    {
        return ['pdf' => 'pdf-references'];
    }

    public function projectsContainerThemes(): array
    {
        return ['pdf' => 'pdf-projects'];
    }

    public function nameThemes(): array
    {
        return ['pdf' => 'pdf-name'];
    }

    public function labelThemes(): array
    {
        return ['pdf' => 'pdf-label'];
    }

    public function sectionThemes(): array
    {
        return ['pdf' => 'pdf-section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['pdf' => 'pdf-section-title'];
    }

    public function itemTitleThemes(): array
    {
        return ['pdf' => 'pdf-item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['pdf' => 'pdf-item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['pdf' => 'pdf-item-details'];
    }

    public function summaryThemes(): array
    {
        return ['pdf' => 'pdf-summary-text'];
    }

    public function contactContainerThemes(): array
    {
        return ['pdf' => 'pdf-contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function listThemes(): array
    {
        return ['pdf' => 'pdf-list'];
    }

    public function imageContainerThemes(): array
    {
        return ['pdf' => 'pdf-image-container'];
    }

    public function imageThemes(): array
    {
        return ['pdf' => 'pdf-image'];
    }

    public function linkThemes(): array
    {
        return ['pdf' => 'pdf-links'];
    }

    public function iconThemes(): array
    {
        return ['pdf' => 'pdf-icon'];
    }

    public function listItemThemes(): array
    {
        return ['pdf' => 'pdf-list-item'];
    }

    public function badgeThemes(): array
    {
        return ['pdf' => 'pdf-badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['pdf' => 'pdf-social-badge'];
    }

    public function dateThemes(): array
    {
        return ['pdf' => 'pdf-date'];
    }

    public function subTitleThemes(): array
    {
        return ['pdf' => 'pdf-subtitle'];
    }

    public function emailThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function urlThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function locationThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function profileThemes(): array
    {
        return ['pdf' => 'pdf-contact-item'];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600;700&display=swap',
        ];
    }

    public function fontFamily(): string
    {
        return "'Source Sans 3', sans-serif";
    }
}
