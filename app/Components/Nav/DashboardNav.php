<?php

namespace App\Components\Nav;

use App\Components\Concerns\IsFluxNavigation;

class DashboardNav
{
    use IsFluxNavigation;

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
                'name' => 'dashboard',
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'home',
            ],
        ];
    }
}
