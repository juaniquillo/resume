<?php

namespace App\Cruds\Squema\Options\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Squema\Options\GeneralOptionsCrud;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class HideEmailFactory
{
    public const NAME = 'hide_email';

    public const LABEL = 'Hide Email';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
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

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), GeneralOptionsCrud::getLivewireGroup());

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
                            'label' => self::LABEL,
                            'description' => 'Hide your email address from the public resume.',
                            'name' => $input->getName(),
                            'align' => 'left',
                            'value' => 1,
                            ...$livewireAttributes,
                        ])
                )
        );
    }
}
