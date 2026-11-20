<?php

namespace App\Cruds\Schema\Locations\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

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
            (new TableCellsRecipe)
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
