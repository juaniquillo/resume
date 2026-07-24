<?php

namespace App\Cruds\Schema\Options\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Options\SectionVisibilityCrud;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class SectionSwitchFactory
{
    public static function make(string $name, string $label): InputInterface
    {
        $label = "Disable the {$label} section";
        $input = new DefaultInput($name, $label);

        self::form($input, $label);
        self::validation($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'sometimes',
                'boolean',
            ]))
        );
    }

    public static function form(InputInterface $input, string $label): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), SectionVisibilityCrud::getLivewireGroup());

        $input->setRecipe(
            (new InputComponentRecipe(
                checkable: true,
            ))
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::SWITCH)
                )
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => $label,
                            'name' => $input->getName(),
                            'value' => 1,
                            ...$livewireAttributes,
                        ])
                )
        );
    }
}




