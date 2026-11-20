<?php

namespace App\Cruds\Schema\CoverLetters\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\CoverLetters\CoverLettersCrud;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\DataContainerInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Juaniquillo\LaravelModelActions\Recipes\LaravelFactoryRecipe;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

class CompanyFactory
{
    const NAME = 'company';

    const LABEL = 'Target Company';

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
                'nullable',
                'string',
                'max:191',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), CoverLettersCrud::getLivewireGroup());
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            ...$livewireAttributes,
                        ])
                )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainerInterface $output, Generator $faker) {
                    $output->set($input->getName(), $faker->company());
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableCellsRecipe(
                value: function ($value, Model $model) {
                    if (empty($value)) {
                        return TableHelpers::emptyValue();
                    }

                    return $value;
                }
            )
        );
    }
}
