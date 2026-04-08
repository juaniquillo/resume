<?php

namespace App\Components\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
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
            
            if ($item['sub-nav'] ?? false) {
                $subItems = [];

                $group = self::group($item['label']);

                foreach ($item['sub-nav'] as $subItem) {
                    $subItems[] = self::single($subItem);
                }
                
                $itemComponent =  $group->setContents($subItems);
                
            } else {
                $itemComponent = self::single($item);
           }
            
            $items[] = $itemComponent;
        }

        // dump($items);

        return $items;
    }

    public static function group(string $title): BackendComponent|ContentComponent
    {
        return FluxComponentBuilder::make('navlist.group')
            ->setAttributes([
                'heading' => $title,
                'expandable' => 'expandable',
                'class' => 'grid',
            ]);

    }

    public static function single(array $item) : BackendComponent|ContentComponent
    {
        $itemComponent = FluxComponentBuilder::make('sidebar.item')
            ->setAttributes([
                'icon' => $item['icon'],
                'href' => route($item['route']),
            ])
            ->setContent($item['label']);

        if (request()->routeIs($item['route'])) {
            $itemComponent->setAttribute('current', 1);
        }

        return $itemComponent;
    }
}
