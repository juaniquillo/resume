<?php

namespace App\Cruds\Schema\Options\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class IsDraftFactory
{
    public const NAME = 'is_draft';

    public const LABEL = 'Toggle Draft Mode';

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
                            'description' => 'Hide your resume from the public while you are working on it.',
                            'name' => $input->getName(),
                            'align' => 'left',
                            'value' => 1,
                            ...$livewireAttributes,
                        ])
                )
        );
    }
}
