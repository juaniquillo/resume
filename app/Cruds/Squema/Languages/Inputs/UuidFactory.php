<?php

namespace App\Cruds\Squema\Languages\Inputs;

use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
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
        self::table($input);

        return $input;
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            (new TableRowsRecipe)
                ->ignore()
        );
    }

    public static function validation(InputInterface $input): void {}

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
            (new LaravelFactoryRecipe)->ignore()
        );
    }
}
