<?php

namespace App\Cruds\Schema\ResumeExport\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

class StatusFactory
{
    public const NAME = 'status';

    public const LABEL = 'Status';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        $input->setRecipe(
            (new InputComponentRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelValidationRulesRecipe)->ignore()
        );

        self::factory($input);
        self::table($input);

        return $input;
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelFactoryRecipe)->ignore()
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableCellsRecipe(
                value: fn ($value) => TableHelpers::statusBadge($value)
            )
        );
    }
}
