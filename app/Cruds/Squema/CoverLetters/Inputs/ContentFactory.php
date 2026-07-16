<?php

namespace App\Cruds\Squema\CoverLetters\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use App\Models\CoverLetter;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class ContentFactory
{
    const NAME = 'content';

    const LABEL = 'Content';

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
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), CoverLettersCrud::getLivewireGroup());
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
                            'badge' => 'required',
                            ...$livewireAttributes,
                        ])
                )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->paragraphs(3, true);
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function ($value, Model $model) {

                    /** @var CoverLetter $coverLetter */
                    $coverLetter = $model;

                    return TableHelpers::summaryModal($value, $coverLetter->id, self::LABEL);
                }
            )
        );
    }
}
