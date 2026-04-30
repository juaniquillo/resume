<?php

namespace App\Components\Nav;

use App\Components\Concerns\HasFluxCards;
use App\Components\Concerns\IsFluxNavigation;

class ToolsNav
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
     *
     *  }
     * > */
    public static function items(): array
    {
        return [
            [
                'name' => 'resume.import',
                'label' => 'Resume Import',
                'route' => 'dashboard.resume.import',
                'icon' => 'arrow-up-tray',
                'description' => 'Import your resume from a JSON file.',
            ],
        ];
    }
}
