<?php

namespace App\Cruds\Squema\Education\Inputs;

use App\Cruds\Actions\General\ModelToExportRecipe;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\TableHelpers;
use Faker\Generator;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class EndsAtFactory
{
    const NAME = 'ends_at';

    const LABEL = 'Ends At';

    const JSON_KEY = 'endDate';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);
        self::import($input);
        self::export($input);

        return $input;
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            new InputComponentRecipe(
                inputValue: FormHelpers::dateFormatOutput(),
                attributeBag: (new DefaultAttributeBag)
                    ->setInputAttributes([
                        'label' => self::LABEL,
                        'type' => 'month',
                    ])
            )
        );
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'nullable',
                'date',
                'after_or_equal:1900-01-01',
                'after_or_equal:starts_at_education',
            ]))
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d');
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        TableHelpers::formatDateOutput($input);
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(name: [self::NAME, self::JSON_KEY]));
    }

    public static function export(InputInterface $input): void
    {
        $input->setRecipe(new ModelToExportRecipe(
            key: self::JSON_KEY
        ));
    }
}
