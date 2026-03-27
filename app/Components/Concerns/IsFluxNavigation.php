<?php

namespace App\Components\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

trait IsFluxNavigation
{
    public static function make(): BackendComponent
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
            $itemComponent = FluxComponentBuilder::make('sidebar.item')
                ->setAttributes([
                    'icon' => $item['icon'],
                    'href' => route($item['route']),
                ])
                ->setContent($item['label']);

            if (request()->routeIs($item['route'])) {
                $itemComponent->setAttribute('current', 1);
            }

            $items[] = $itemComponent;
        }

        // dump($items);

        return $items;
    }
}
