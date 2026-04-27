<?php

namespace App\Components\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

trait IsFluxNavigation
{
    public static function makeNav(): BackendComponent
    {
        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents(
                self::navItems()
            );
    }

    public static function navItems(): array
    {
        $items = [];

        foreach (self::items() as $item) {

            if ($item['sub_nav'] ?? false) {
                $subItems = [];

                $group = self::group($item['label']);

                foreach ($item['sub_nav'] as $subItem) {
                    $subItems[] = self::single($subItem);
                }

                $items[] = $group->setContents($subItems);

                continue;

            }

            $items[] = self::single($item);
        }

        // dump($items);

        return $items;
    }

    public static function group(string $title): BackendComponent|ContentComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::NAVLIST_GROUP)
            ->setAttributes([
                'heading' => $title,
                'expandable' => 'expandable',
                'class' => 'grid',
            ]);

    }

    public static function single(array $item): BackendComponent|ContentComponent
    {
        $itemComponent = FluxComponentBuilder::make(FluxComponentEnum::SIDEBAR_ITEM)
            ->setAttributes([
                'icon' => $item['icon'],
                'href' => route($item['route']),
            ])
            ->setContent($item['label']);

        $active = isset($item['active']) ? $item['active'] : [];

        if (request()->routeIs($item['route'], ...$active)) {
            $itemComponent->setAttribute('current', 1);
        }

        return $itemComponent;
    }
}
