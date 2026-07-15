<?php

namespace App\Cruds\Squema\Works\Inputs;

use App\Cruds\Actions\General\FormatDateRecipe;
use App\Cruds\Actions\General\ModelToExportRecipe;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationMessagesRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\Works\WorksCrud;
use Faker\Generator;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class StartsAtFactory
{
    const NAME = 'starts_at';

    const LABEL = 'Starts At';

    const JSON_KEY = 'startDate';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);
        self::import($input);
        self::export($input);

        self::dateFormat($input);

        return $input;
    }

    public static function dateFormat(InputInterface $input): void
    {
        $input->setRecipe(new FormatDateRecipe(isDate: true));
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(name: [self::NAME, self::JSON_KEY]));
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'date',
                'after_or_equal:1900-01-01',
            ]))
        );

        $input->setRecipe(
            (new LaravelValidationMessagesRecipe(
                messages: 'The :attribute field must be a valid date after or equal to January 1st, 1900.')
            )
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), WorksCrud::getLivewireGroup());

        $input->setRecipe(
            new InputComponentRecipe(
                inputValue: FormHelpers::dateFormatOutput(),
                attributeBag: (new DefaultAttributeBag)
                    ->setInputAttributes([
                        'label' => self::LABEL,
                        'badge' => 'required',
                        'type' => 'month',
                        ...$livewireAttributes,
                    ]),
            )
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

    public static function export(InputInterface $input): void
    {
        $input->setRecipe(new ModelToExportRecipe(
            key: self::JSON_KEY
        ));
    }
}
