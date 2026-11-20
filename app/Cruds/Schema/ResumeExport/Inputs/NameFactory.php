<?php

namespace App\Cruds\Schema\ResumeExport\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\DataContainerInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

class NameFactory
{
    public const NAME = 'name';

    public const LABEL = 'Name';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
                'max:191',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'name' => $input->getName(),
                            'badge' => 'required',
                        ])
                )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainerInterface $output, Generator $faker) {
                    $output->set($input->getName(), $faker->optional()->words(3, true));
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            (new TableCellsRecipe(
                value: fn ($value, Model $model) => $model->{$input->getName()} ?? TableHelpers::emptyValue()
            ))
        );
    }
}
