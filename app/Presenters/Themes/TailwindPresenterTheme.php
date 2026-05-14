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
}
