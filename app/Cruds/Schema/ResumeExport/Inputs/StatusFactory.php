<?php

namespace App\Cruds\Schema\ResumeExport\Inputs;

use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use App\Enums\ProcessStatus;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

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
            new LaravelFactoryRecipe(
                callback: fn () => ProcessStatus::PENDING->value
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: fn ($value) => TableHelpers::statusBadge($value)
            )
        );
    }
}
