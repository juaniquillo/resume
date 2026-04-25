<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

class TableHelpers
{
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
}
