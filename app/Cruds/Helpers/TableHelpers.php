<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
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
                'button' => FluxComponentBuilder::make('modal.trigger')
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    ->setContent(
                        FluxComponentBuilder::make('button')
                            ->setAttribute('variant', $triggerType)
                            ->setAttribute('size', 'xs')
                            ->setContent($buttonLabel)
                    ),
                'modal' => FluxComponentBuilder::make('modal')
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    // ->setAttribute(':dismissible', 'false')
                    ->setContents([
                        FluxComponentBuilder::make('heading')
                            ->setContent($heading),
                        $content,
                    ]),
            ]);
    }
}
