<?php

namespace App\Cruds\Squema\Locations\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class BasicsFactory
{
    const NAME = 'basics_locations';

    const LABEL = 'Basics';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        $input->setRecipe(
            (new InputComponentRecipe)
                ->ignore()
        );

        return $input;
    }
}
