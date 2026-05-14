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
}
