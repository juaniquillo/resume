<?php

namespace App\Components\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

trait HasFluxCards
{
    public static function makeCards(): BackendComponent
    {
        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents(
                self::cardLinks()
            );
    }

    public static function cardLinks(): array
    {
        $cards = [];

        foreach (self::cards() as $item) {

            $cards[] = ComponentBuilder::make(ComponentEnum::LINK)
                ->setAttributes([
                    'href' => $item['href'],
                    'aria-label' => $item['label'],
                    'class' => 'dashboard-card-link',
                ])
                ->setContent(
                    self::card($item)
                );
        }

        return $cards;
    }

    public static function card(array $item): BackendComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::CARD)
            ->setAttributes([
                'size' => 'sm',
            ])
            ->setAttribute('class', 'dashboard-card')
            ->setContents(
                self::cardContents($item)
            );
    }

    public static function cardContents(array $item): array
    {
        return [
            self::cardHeading($item),
            self::cardText($item),
        ];
    }

    public static function cardHeading(array $item): BackendComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::HEADING)
            ->setAttribute('class', 'flex items-center gap-1')
            ->setContents([
                self::cardIcon($item),
                $item['label'],
            ]);
    }

    public static function cardText(array $item): BackendComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::TEXT)
            ->setAttribute('class', 'mt-2')
            ->setContent($item['description'] ?? '');
    }

    public static function cardIcon(array $item): BackendComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::ICON)
            ->setAttribute('class', 'text-zinc-400')
            ->setAttributes([
                'name' => $item['icon'],
            ])
            ->setAttributes([
                'variant' => 'micro',
            ]);
    }
}
