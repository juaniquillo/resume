<?php

namespace App\Components\Nav;

use App\Components\Concerns\HasFluxCards;
use App\Components\Concerns\IsFluxNavigation;

class DashboardNav
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
     *    ignore-dashboard?: bool,
     *    sub-nav?: array<array{
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
                'label' => 'Work Experience',
                'route' => 'dashboard.works',
                'active' => ['dashboard.works.edit', 'dashboard.works.highlights', 'dashboard.works.highlights.edit'],
                'icon' => 'briefcase',
                'description' => 'Add, edit and manage your works.',
            ],
            [
                'name' => 'volunteers',
                'label' => 'Volunteers',
                'route' => 'dashboard.volunteers',
                'active' => ['dashboard.volunteers.edit'],
                'icon' => 'user-group',
                'description' => 'Add, edit and manage your volunteer work.',
            ],
            [
                'name' => 'education',
                'label' => 'Education',
                'route' => 'dashboard.education',
                'active' => ['dashboard.education.edit'],
                'icon' => 'academic-cap',
                'description' => 'Add, edit and manage your education.',
            ],
            [
                'name' => 'awards',
                'label' => 'Awards',
                'route' => 'dashboard.awards',
                'active' => ['dashboard.awards.edit'],
                'icon' => 'trophy',
                'description' => 'Add, edit and manage your awards.',
            ],
        ];
    }

    public static function cards(): array
    {
        $links = [];

        foreach (self::items() as $item) {
            if ($item['ignore-dashboard'] ?? null) {
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
