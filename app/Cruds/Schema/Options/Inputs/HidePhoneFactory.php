<?php

namespace App\Cruds\Schema\Options\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use Faker\Generator;
use Juaniquillo\CrudAssistant\Contracts\DataContainerInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;

class HidePhoneFactory
{
    public const NAME = 'hide_phone';

    public const LABEL = 'Hide Phone';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::factory($input);
        self::form($input);
        self::validation($input);

        return $input;
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainerInterface $output, Generator $faker) {
                    $output->set($input->getName(), false);
                }
            )
        );
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
                            'description' => 'Hide your phone number from the public resume.',
                            'name' => $input->getName(),
                            'align' => 'left',
                            'value' => 1,
                            ...$livewireAttributes,
                        ])
                )
        );
    }
}
