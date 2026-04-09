<?php

namespace App\Components\Nav;

use App\Components\Concerns\HasFluxCards;
use App\Components\Concerns\IsFluxNavigation;

class DashboardNav
{
    use IsFluxNavigation,
        HasFluxCards;

    /**
     * @return array<
     *  array{
     *    name: string,
     *    label: string,
     *    route: string,
     *    icon: string,
     *    description?: string,
     *    sub-nav?: array<array{
     *     name: string,
     *     label: string,
     *     route: string,
     *     icon: string,
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
                'ignore-dashboard' => true,
            ],
            [
                'name' => 'basics',
                'label' => 'Basics',
                'description' => 'Update basic info.',
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
            [
                'name' => 'works',
                'label' => 'Works',
                'route' => 'dashboard.works',
                'icon' => 'briefcase',
                'description' => 'Add, edit and manage your works.',
            ],
            // [
            //     'label' => 'Works',
            //     'description' => 'Add, edit and manage your works.',
            //     'sub-nav' => [
            //         [
            //             'name' => 'works',
            //             'label' => 'Manage Works',
            //             'route' => 'dashboard.works',
            //             'icon' => 'briefcase',
            //         ],
            //         [
            //             'name' => 'works.highlights',
            //             'label' => 'Highlights',
            //             'route' => 'dashboard.works.highlights',
            //             'icon' => 'briefcase',
            //         ]
            //     ],
            // ],
        ];
    }

    public  static function cards(): array
    {
        $links = [];

        foreach(self::items() as $item) {
            if($item['ignore-dashboard'] ?? false) {
                continue;
            }

            $firstSubNav = $item['sub-nav'] ?? false ? $item['sub-nav'][0] : null;

            $links[] = [
                'label' => $item['label'],
                'href' => $firstSubNav ? route($firstSubNav['route']) : route($item['route']),
                'icon' => $firstSubNav ? $firstSubNav['icon'] : $item['icon'],
                'description' => $item['description'] ?? null,
            ];

        }
        
        return $links;
    }
}
