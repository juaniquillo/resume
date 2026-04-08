<?php

namespace App\Components\Nav;

use App\Components\Concerns\IsFluxNavigation;

class DashboardNav
{
    use IsFluxNavigation;

    /**
     * @return array<
     *  array{
     *      name: string,
     *      label: string,
     *      route: string,
     *      icon: string,
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
            [
                'name' => 'basics',
                'label' => 'Basics',
                'icon' => 'document-text',
                'sub-nav' => [
                    [
                        'name' => 'basics',
                        'label' => 'Basic Info',
                        'route' => 'dashboard.basics',
                        'icon' => 'document-text',
                    ],
                    [
                        'name' => 'basics.locations',
                        'label' => 'Locations',
                        'route' => 'dashboard.basics.locations',
                        'icon' => 'map-pin',
                    ],
                    [
                        'name' => 'basics.profiles',
                        'label' => 'Profiles',
                        'route' => 'dashboard.basics.profiles',
                        'icon' => 'user',
                    ],
                ],
            ],
        ];
    }
}
