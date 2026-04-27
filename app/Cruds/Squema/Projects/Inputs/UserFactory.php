<?php

namespace App\Cruds\Squema\Projects\Inputs;

use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class UserFactory
{
    const NAME = 'user_id';

    const LABEL = 'User';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        $input->setRecipe(
            (new InputComponentRecipe)->ignore()
        );

        $input->setRecipe(
            (new TableRowsRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelFactoryRecipe)->ignore()
        );

        return $input;
    }
}
