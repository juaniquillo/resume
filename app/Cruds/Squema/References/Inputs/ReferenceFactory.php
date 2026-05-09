<?php

namespace App\Cruds\Squema\References\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class ReferenceFactory
{
    const NAME = 'reference';

    const LABEL = 'Reference';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::validation($input);
        self::form($input);
        self::factory($input);
        self::import($input);

        return $input;
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(name: ['reference', 'keywords']));
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::TEXTAREA)
                )
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'placeholder' => 'Enter the reference text...',
                        ])
                )
        );
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelValidationRulesRecipe(
                rules: [
                    'nullable',
                    'string',
                ]
            )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, $faker) {
                    $output->{ $input->getName() } = $faker->paragraph();
                }
            )
        );
    }
}
