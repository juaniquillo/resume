<?php

namespace App\Components\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

/**
 * @method static array items()
 */
trait IsFluxNavigation
{
    public static function makeNav(): BackendComponent|Htmlable
    {
        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents(
                self::navItems()
            );
    }

    public static function cacheNav(): string
    {
        $key = self::getKeyCache();

        return Cache::remember($key, now()->addWeek(), function () {
            return self::makeNav()->toHtml();
        });

    }

    public static function getKeyCache(): string
    {
        $class = md5(self::class);

        return "cache-nav-{$class}";
    }

    public static function clearCache(): void
    {
        Cache::forget(self::getKeyCache());
    }

    public static function items(): array
    {
        return [];
    }

    public static function navItems(): array
    {
        $items = [];
        $request = request();

        foreach (self::items() as $item) {

            if ($item['sub_nav'] ?? false) {
                $subItems = [];

                $expandable = false;

                foreach ($item['sub_nav'] as $subItem) {
                    $active = self::isActive($subItem, $request);
                    if ($active && ! $expandable) {
                        $expandable = true;
                    }
                    $subItems[] = self::single($subItem, $active);
                }

                $group = self::group($item['label'], $expandable);
                $items[] = $group->setContents($subItems);

                continue;

            }

            $items[] = self::single($item, self::isActive($item, $request));
        }

        // dump($items);

        return $items;
    }

    public static function group(string $title, bool $expandable = false): BackendComponent|ContentComponent
    {
        $attr = [
            'heading' => $title,
            'class' => 'grid',
            'expandable' => 'expandable',

        ];
        if (! $expandable) {
            $attr['expanded'] = '0';
        }

        return FluxComponentBuilder::make(FluxComponentEnum::NAVLIST_GROUP)
            ->setAttributes($attr);

    }

    public static function single(array $item, bool $current = false): BackendComponent|ContentComponent
    {
        $itemComponent = FluxComponentBuilder::make(FluxComponentEnum::SIDEBAR_ITEM)
            ->setAttributes([
                'icon' => $item['icon'],
                'href' => route($item['route']),
                'wire:navigate' => '',
            ])
            ->setContent($item['label']);

        if ($current) {
            $itemComponent->setAttribute('current', 1);
        }

        return $itemComponent;
    }

    public static function isActive(array $item, Request $request)
    {
        $active = isset($item['active']) ? $item['active'] : [];
        $isCurrent = false;
        if ($request->routeIs($item['route'], ...$active)) {
            $isCurrent = true;
        }

        return $isCurrent;
    }
}



