<?php

namespace App\Livewire\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

trait IsModal
{
    public function button(): BackendComponent|CompoundComponent
    {
        $button = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            // ->setAttribute('wire:click', 'openModal()')
            ->setAttribute('type', 'button');

        return $button;
    }
}
