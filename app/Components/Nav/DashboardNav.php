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
                'route' => 'dashboard.basics',
                'icon' => 'document-text',
            ],
        ];
    }
}
