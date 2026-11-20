<?php

namespace App\Cruds\Schema\Locations\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

class BasicsFactory
{
    const NAME = 'basics';

    const LABEL = 'Basics';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        $input->setRecipe(
            (new InputComponentRecipe)->ignore()
        );

        $input->setRecipe(
            (new TableCellsRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelFactoryRecipe)->ignore()
        );

        return $input;
    }
}
