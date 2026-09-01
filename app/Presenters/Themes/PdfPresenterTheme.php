<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class PdfPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['pdf' => 'container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['pdf' => 'basics-container'];
    }

    public function summaryContainerThemes(): array
    {
        return ['pdf' => 'summary-container'];
    }

    public function workContainerThemes(): array
    {
        return ['pdf' => 'work-container'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['pdf' => 'volunteers-container'];
    }

    public function educationContainerThemes(): array
    {
        return ['pdf' => 'education-container'];
    }

    public function awardsContainerThemes(): array
    {
        return ['pdf' => 'awards-container'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['pdf' => 'certificates-container'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['pdf' => 'publications-container'];
    }

    public function skillsContainerThemes(): array
    {
        return ['pdf' => 'skills-container'];
    }

    public function languagesContainerThemes(): array
    {
        return ['pdf' => 'languages-container'];
    }

    public function interestsContainerThemes(): array
    {
        return ['pdf' => 'interests-container'];
    }

    public function referencesContainerThemes(): array
    {
        return ['pdf' => 'references-container'];
    }

    public function projectsContainerThemes(): array
    {
        return [
            'pdf' => 'projects-container',
        ];
    }

    public function downloadsContainerThemes(): array
    {
        return [
            'pdf' => 'downloads-container',
        ];
    }

    public function basicsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'basics-inner-container',
        ];
    }

    public function summaryInnerContainerThemes(): array
    {
        return [
            'pdf' => 'summary-inner-container',
        ];
    }

    public function workInnerContainerThemes(): array
    {
        return [
            'pdf' => 'work-inner-container',
        ];
    }

    public function volunteersInnerContainerThemes(): array
    {
        return [
            'pdf' => 'volunteers-inner-container',
        ];
    }

    public function educationInnerContainerThemes(): array
    {
        return [
            'pdf' => 'education-inner-container',
        ];
    }

    public function awardsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'awards-inner-container',
        ];
    }

    public function certificatesInnerContainerThemes(): array
    {
        return [
            'pdf' => 'certificates-inner-container',
        ];
    }

    public function publicationsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'publications-inner-container',
        ];
    }

    public function skillsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'skills-inner-container',
        ];
    }

    public function languagesInnerContainerThemes(): array
    {
        return [
            'pdf' => 'languages-inner-container',
        ];
    }

    public function interestsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'interests-inner-container',
        ];
    }

    public function referencesInnerContainerThemes(): array
    {
        return [
            'pdf' => 'references-inner-container',
        ];
    }

    public function projectsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'projects-inner-container',
        ];
    }

    public function downloadsInnerContainerThemes(): array
    {
        return [
            'pdf' => 'downloads-inner-container',
        ];
    }

    public function nameThemes(): array
    {
        return ['pdf' => 'name'];
    }

    public function labelThemes(): array
    {
        return ['pdf' => 'label'];
    }

    public function sectionThemes(): array
    {
        return ['pdf' => 'section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['pdf' => 'section-title'];
    }

    public function sectionInnerThemes(): array
    {
        return ['pdf' => 'section-inner'];
    }

    public function itemTitleThemes(): array
    {
        return ['pdf' => 'item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['pdf' => 'item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['pdf' => 'item-details'];
    }

    public function summaryThemes(): array
    {
        return ['pdf' => 'summary'];
    }

    public function contactContainerThemes(): array
    {
        return ['pdf' => 'contact-container'];
    }

    public function badgeContainerThemes(): array
    {
        return ['pdf' => 'badge-container'];
    }

    public function contactInnerContainerThemes(): array
    {
        return ['pdf' => 'contact-container'];
    }

    public function contactItemThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function listThemes(): array
    {
        return ['pdf' => 'list'];
    }

    public function imageContainerThemes(): array
    {
        return ['pdf' => 'image-container'];
    }

    public function imageThemes(): array
    {
        return ['pdf' => 'image'];
    }

    public function linkThemes(): array
    {
        return ['pdf' => 'links'];
    }

    public function iconThemes(): array
    {
        return ['pdf' => 'icon'];
    }

    public function listItemThemes(): array
    {
        return ['pdf' => 'list-item'];
    }

    public function badgeThemes(): array
    {
        return ['pdf' => 'badge'];
    }

    public function keywordBadgeThemes(): array
    {
        return ['pdf' => 'badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['pdf' => 'social-badge'];
    }

    public function dateThemes(): array
    {
        return ['pdf' => 'date'];
    }

    public function subTitleThemes(): array
    {
        return ['pdf' => 'subtitle'];
    }

    public function emailThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function urlThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function locationThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function profileThemes(): array
    {
        return ['pdf' => 'contact-item'];
    }

    public function coverLetterContainerThemes(): array
    {
        return [
            'pdf' => 'cover-letter-container',
        ];
    }

    public function nameContainerThemes(): array
    {
        return [
            'pdf' => 'name-container',
        ];
    }

    public function coverLetterThemes(): array
    {
        return [
            'pdf' => 'summary',
        ];
    }

    public function fontUrls(): array
    {
        return [
            'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600;700&display=swap',
        ];
    }

    public function localFonts(): array
    {
        return [
            ['family' => 'Libre Baskerville', 'path' => 'fonts/libre-baskerville-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Libre Baskerville', 'path' => 'fonts/libre-baskerville-bold.woff2', 'weight' => '700', 'style' => 'normal'],
            ['family' => 'Libre Baskerville', 'path' => 'fonts/libre-baskerville-italic.woff2', 'weight' => '400', 'style' => 'italic'],
            ['family' => 'Source Sans 3', 'path' => 'fonts/source-sans-3-light.woff2', 'weight' => '300', 'style' => 'normal'],
            ['family' => 'Source Sans 3', 'path' => 'fonts/source-sans-3-regular.woff2', 'weight' => '400', 'style' => 'normal'],
            ['family' => 'Source Sans 3', 'path' => 'fonts/source-sans-3-semibold.woff2', 'weight' => '600', 'style' => 'normal'],
            ['family' => 'Source Sans 3', 'path' => 'fonts/source-sans-3-bold.woff2', 'weight' => '700', 'style' => 'normal'],
        ];
    }

    public function fontFamily(): string
    {
        return "'Source Sans 3', sans-serif";
    }
}
