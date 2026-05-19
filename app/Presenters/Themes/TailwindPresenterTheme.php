<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class TailwindPresenterTheme implements PresenterTheme
{
    public function containerThemes(): array
    {
        return [
            'spacing' => 'p-md',
        ];
    }

    public function basicsContainerThemes(): array
    {
        return [
            'spacing' => 'm-bottom-md',
        ];
    }

    public function summaryContainerThemes(): array
    {
        return [];
    }

    public function workContainerThemes(): array
    {
        return [];
    }

    public function volunteersContainerThemes(): array
    {
        return [];
    }

    public function educationContainerThemes(): array
    {
        return [];
    }

    public function awardsContainerThemes(): array
    {
        return [];
    }

    public function certificatesContainerThemes(): array
    {
        return [];
    }

    public function publicationsContainerThemes(): array
    {
        return [];
    }

    public function skillsContainerThemes(): array
    {
        return [];
    }

    public function languagesContainerThemes(): array
    {
        return [];
    }

    public function interestsContainerThemes(): array
    {
        return [];
    }

    public function referencesContainerThemes(): array
    {
        return [];
    }

    public function projectsContainerThemes(): array
    {
        return [];
    }

    public function nameThemes(): array
    {
        return [];
    }

    public function labelThemes(): array
    {
        return [];
    }

    public function sectionThemes(): array
    {
        return [
            'spacing' => 'm-bottom-lg',
        ];
    }

    public function sectionTitleThemes(): array
    {
        return [
            'spacing' => 'm-bottom-sm',
        ];
    }

    public function itemTitleThemes(): array
    {
        return [
            'spacing' => 'm-bottom-xs',
        ];
    }

    public function itemContainerThemes(): array
    {
        return [
            'spacing' => 'm-bottom-md',
        ];
    }

    public function itemDetailsThemes(): array
    {
        return [];
    }

    public function summaryThemes(): array
    {
        return [];
    }

    public function contactContainerThemes(): array
    {
        return [
            'spacing' => ['m-top-sm', 'm-bottom-sm'],
        ];
    }

    public function contactItemThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
        ];
    }

    public function listThemes(): array
    {
        return [
            'spacing' => 'm-top-sm',
        ];
    }

    public function imageThemes(): array
    {
        return [
            'spacing' => 'm-bottom-sm',
        ];
    }

    public function linkThemes(): array
    {
        return [];
    }

    public function iconThemes(): array
    {
        return [
            'size' => 'size-4',
        ];
    }

    public function listItemThemes(): array
    {
        return [];
    }

    public function badgeThemes(): array
    {
        return [];
    }

    public function dateThemes(): array
    {
        return [];
    }

    public function subTitleThemes(): array
    {
        return [];
    }

    public function emailThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
        ];
    }

    public function phoneThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
        ];
    }

    public function urlThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
        ];
    }

    public function locationThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
        ];
    }

    public function profileThemes(): array
    {
        return [
            'spacing' => 'm-right-sm',
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
