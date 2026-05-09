<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class TableHelpers
{
    public static function make(): static
    {
        return new self;
    }

    public static function tableModal(int $id, string|BackendComponent|CompoundComponent $content, string $heading = '', string $triggerType = 'primary', string $buttonLabel = 'View'): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => FluxComponentBuilder::make(FluxComponentEnum::MODAL_TRIGGER)
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    ->setContent(
                        FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                            ->setAttribute('variant', $triggerType)
                            ->setAttribute('size', 'xs')
                            ->setTheme('cursor', 'pointer')
                            ->setContent($buttonLabel)
                    ),
                'modal' => FluxComponentBuilder::make(FluxComponentEnum::MODAL)
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    // ->setAttribute(':dismissible', 'false')
                    ->setContents([
                        FluxComponentBuilder::make(FluxComponentEnum::HEADING)
                            ->setContent($heading),
                        $content,
                    ]),
            ]);
    }

    public static function editButton(string $route): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', $route)
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setAttribute('icon', 'pencil-square')
            ->setTheme('cursor', 'pointer');
    }

    public static function deleteButton(string $route): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', $route)
            ->setAttribute('method', 'delete')
            ->setContent(
                FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                    ->setAttribute('type', 'submit')
                    ->setContent('Delete')
                    ->setAttribute('size', 'xs')
                    ->setAttribute('variant', 'danger')
                    ->setAttribute('icon', 'trash')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this record?')")
                    ->setTheme('cursor', 'pointer'),
            );
    }

    public static function highlightsButton(string $route): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', $route)
            ->setContent('Highlights')
            ->setAttribute('variant', 'primary')
            ->setAttribute('icon', 'sun')
            ->setAttribute('color', 'amber')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');

    }

    public static function errorTooltip(string $error, string|BackendComponent|CompoundComponent $trigger, string $position = 'top'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::TOOLTIP)
            ->setAttribute('content', $error)
            ->setAttribute('position', $position)
            ->setContent($trigger);
    }
}
