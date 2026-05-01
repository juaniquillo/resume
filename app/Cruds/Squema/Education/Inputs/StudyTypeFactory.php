<?php

namespace App\Cruds\Squema\Education\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Recipes\SelectOptionComponentRecipe;
use App\Enums\EducationLevel;
use App\Models\Education;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class StudyTypeFactory
{
    const NAME = 'study_type';

    const LABEL = 'Study Type';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);

        $input->setSubElements(self::options());

        self::validation($input);
        self::factory($input);
        self::table($input);
        self::import($input);

        return $input;
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(name: ['study_type', 'studyType'], default: ''));
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                new Enum(EducationLevel::class),
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            new InputComponentRecipe(
                componentBag: (new DefaultComponentBag)
                    ->setInputType(FluxComponentEnum::SELECT),
                attributeBag: (new DefaultAttributeBag)
                    ->setInputAttributes([
                        'label' => self::LABEL,
                        'badge' => 'required',
                    ]),
            )
        );
    }

    public static function optionsArray(): array
    {
        $levels = [];

        $levels[] = [
            'name' => 'choose',
            'label' => 'Choose...',
            'value' => '',
        ];

        foreach (EducationLevel::cases() as $level) {
            $levels[] = [
                'name' => $level->value,
                'label' => $level->label(),
            ];
        }

        return $levels;
    }

    public static function options(): InputCollectionInterface
    {
        $options = [];

        foreach (self::optionsArray() as $optionArray) {

            $option = new DefaultInput($optionArray['name'], $optionArray['label']);

            $optionRecipe = new SelectOptionComponentRecipe(
                inputValue: $optionArray['value'] ?? $optionArray['name'],
                componentBag: (new DefaultComponentBag)
                    ->setInputType(FluxComponentEnum::OPTION)
            );

            $option->setRecipe($optionRecipe);

            $options[] = $option;
        }

        return CrudAssistant::make($options);
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->randomElement(EducationLevel::cases())->value;
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (?string $value, Model|Education $model) {
                    if (! $value) {
                        return '';
                    }

                    $valueNew = $value;
                    $color = 'zinc';

                    $enum = EducationLevel::tryFrom($value);

                    if ($enum) {
                        $valueNew = $enum->label();
                        $color = $enum->colors();
                    }

                    return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                        ->setAttributes([
                            'color' => $color,
                        ])
                        ->setContent($valueNew);
                }
            )
        );
    }
}
