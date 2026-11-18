<?php

namespace App\Cruds\Schema\Languages\Inputs;

use App\Cruds\Actions\General\ModelToExportRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

class UserFactory
{
    const NAME = 'user';

    const LABEL = 'User';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        $input->setRecipe((new InputComponentRecipe)->ignore());

        $input->setRecipe((new LaravelFactoryRecipe)->ignore());

        $input->setRecipe((new TableCellsRecipe)->ignore());

        $input->setRecipe((new ModelToExportRecipe)->ignore());

        return $input;
    }
}
