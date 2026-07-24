<?php

namespace App\Cruds\Schema\Basics\Renderers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Basics\BasicsCrud;
use App\Cruds\Schema\Basics\Inputs\ImageFactory;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class BasicsFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderFull(BasicsCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }

    public function saveButton(BasicsCrud $crud, string $label = 'Save'): BackendComponent|CompoundComponent
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes(ImageFactory::NAME, BasicsCrud::getLivewireGroup());

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttributes([
                'wire:loading.attr' => 'disabled',
                'wire:target' => $livewireAttributes['wire:model'],
            ])
            ->setTheme('cursor', 'pointer')
            ->setContent(__($label));
    }
}
