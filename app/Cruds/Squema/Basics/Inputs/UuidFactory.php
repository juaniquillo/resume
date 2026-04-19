<?php

namespace App\Cruds\Squema\Basics\Inputs;

use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use Faker\Generator;
use Illuminate\Support\Facades\Route;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class UuidFactory
{
    const NAME = 'uuid';

    const LABEL = 'UUID';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        if (Route::currentRouteName() === 'dashboard.works.store') {
            $input->setRecipe(
                (new LaravelValidationRulesRecipe([
                    'required',
                ]))
            );
        }

    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->ignore()
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $fake) {
                    $output->{ $input->getName() } = $fake->uuid;
                }
            )
        );
    }
}
