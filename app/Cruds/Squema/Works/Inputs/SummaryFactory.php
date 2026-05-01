<?php

namespace App\Cruds\Squema\Works\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Work;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class SummaryFactory
{
    const NAME = 'summary';

    const LABEL = 'Summary';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);
        self::import($input);

        return $input;
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe);
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'nullable',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setValueAsInputContent(true)
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::TEXTAREA)
                )
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                        ])
                )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->paragraph;
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function ($value, Model $model) {

                    if (! $value) {
                        return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                            ->setAttribute('color', 'red')
                            ->setContent('empty');

                    }

                    /** @var Work $work */
                    $work = $model;
                    $modalContent = LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                        ->setContent($value)
                        ->setTheme('spacing', 'm-top-sm')
                        ->setTheme('text', 'nl2br');

                    return TableHelpers::tableModal($work->id, $modalContent, SummaryFactory::LABEL, 'ghost');
                }
            )
        );
    }
}
