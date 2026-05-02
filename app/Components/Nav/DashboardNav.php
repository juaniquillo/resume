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
                'ignore_cards' => true,
            ],
            [
                'name' => 'basics',
                'label' => 'Basics',
                'description' => 'Start here. Update basic info.',
                'sub_nav' => [
                    [
                        'name' => 'basics',
                        'label' => 'Basic Info',
                        'route' => 'dashboard.basics',
                        'icon' => 'document-text',
                    ],
                    [
                        'name' => 'basics.location',
                        'label' => 'Locations',
                        'route' => 'dashboard.basics.location',
                        'active' => ['dashboard.basics.location.edit'],
                        'icon' => 'map-pin',
                    ],
                    [
                        'name' => 'basics.profiles',
                        'label' => 'Profiles',
                        'route' => 'dashboard.basics.profiles',
                        'active' => ['dashboard.basics.profiles.edit'],
                        'icon' => 'at-symbol',
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
                'active' => ['dashboard.volunteers.edit', 'dashboard.volunteers.highlights', 'dashboard.volunteers.highlights.edit'],
                'icon' => 'user-group',
                'description' => 'Add, edit and manage your volunteer work.',
            ],
            [
                'name' => 'education',
                'label' => 'Education',
                'route' => 'dashboard.education',
                'active' => ['dashboard.education.edit', 'dashboard.education.courses', 'dashboard.education.courses.edit'],
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
            [
                'name' => 'certificates',
                'label' => 'Certificates',
                'route' => 'dashboard.certificates',
                'active' => ['dashboard.certificates.edit'],
                'icon' => 'document-arrow-up',
                'description' => 'Add, edit and manage your certificates.',
            ],
            [
                'name' => 'publications',
                'label' => 'Publications',
                'route' => 'dashboard.publications',
                'active' => ['dashboard.publications.edit'],
                'icon' => 'book-open',
                'description' => 'Add, edit and manage your publications.',
            ],
            [
                'name' => 'skills',
                'label' => 'Skills',
                'route' => 'dashboard.skills',
                'active' => ['dashboard.skills.edit'],
                'icon' => 'wrench',
                'description' => 'Add, edit and manage your skills.',
            ],
            [
                'name' => 'languages',
                'label' => 'Languages',
                'route' => 'dashboard.languages',
                'active' => ['dashboard.languages.edit'],
                'icon' => 'language',
                'description' => 'Add, edit and manage your language proficiencies.',
            ],
            [
                'name' => 'interests',
                'label' => 'Interests',
                'route' => 'dashboard.interests',
                'active' => ['dashboard.interests.edit'],
                'icon' => 'sparkles',
                'description' => 'Add, edit and manage your interests.',
            ],
            [
                'name' => 'references',
                'label' => 'References',
                'route' => 'dashboard.references',
                'active' => ['dashboard.references.edit'],
                'icon' => 'bookmark',
                'description' => 'Add, edit and manage your references.',
            ],
            [
                'name' => 'projects',
                'label' => 'Projects',
                'route' => 'dashboard.projects',
                'active' => ['dashboard.projects.edit', 'dashboard.projects.highlights', 'dashboard.projects.highlights.edit'],
                'icon' => 'folder',
                'description' => 'Add, edit and manage your projects.',
            ],
        ];
    }
}
