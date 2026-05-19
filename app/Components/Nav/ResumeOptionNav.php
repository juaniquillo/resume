<?php

namespace App\Components\Nav;

use App\Components\Concerns\HasFluxCards;
use App\Components\Concerns\IsFluxNavigation;

class ResumeOptionNav
{
    use HasFluxCards,
        IsFluxNavigation;

    /**
     * @return array<
     *  array{
     *    name: string,
     *    label: string,
     *    route?: string,
     *    active?: array,
     *    icon?: string,
     *    description?: string,
     *    ignore_cards?: bool,
     *    sub_nav?: array<array{
     *     name: string,
     *     label: string,
     *     route: string,
     *     icon: string,
     *     active?: array,
     *   }>
     *  }
     * > */
    public static function items(): array
    {
        return [
            [
                'name' => 'resume.slug',
                'label' => 'Update Link',
                'route' => 'dashboard.resume.slug',
                'icon' => 'link',
                'description' => 'Update your resume slug.',
            ],
            [
                'name' => 'resume.visibility',
                'label' => 'Section Visibility',
                'route' => 'dashboard.resume.visibility',
                'icon' => 'eye',
                'description' => 'Enable or disable resume sections.',
            ],
        ];
    }
}
