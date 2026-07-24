<?php

namespace App\Cruds\Schema\ResumeExport\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use App\Enums\ResumeExportType;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultDisableBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Groups\SoleInputGroup;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Stringable;

use function Juaniquillo\BackendComponents\isBackedEnum;

class ExportTypeSelectFactory
{
    public const NAME = 'type';

    public const LABEL = 'Choose Export Format';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);

        $input->setSubElements(self::options());

        self::validation($input);
        self::table($input);
        self::factory($input);

        return $input;
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(callback: fn () => fake()->randomElement(ResumeExportType::cases())->value)
        );
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
                Rule::enum(ResumeExportType::class),
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::SELECT)
                )
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

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                label: 'Type',
                value: function (Stringable|BackedEnum|string|array|null $value, Model $model) {

                    if ($value === null) {
                        return TableHelpers::emptyValue();
                    }

                    if (isBackedEnum($value)) {
                        /** @var ResumeExportType $enum */
                        $enum = $value;

                        return TableHelpers::badge($enum->label(), $enum->color());
                    }

                    if (is_array($value)) {
                        return implode(', ', $value);
                    }

                    return $value;
                }
            )
        );
    }

    public static function optionsArray(): array
    {
        $options = [
            [
                'name' => 'choose',
                'label' => 'Choose...',
                'value' => '',
            ],
        ];

        foreach (ResumeExportType::cases() as $value) {
            $options[] = [
                'name' => $value->value,
                'label' => $value->label(),
            ];
        }

        return $options;
    }

    public static function options(): InputCollectionInterface
    {
        $options = [];

        foreach (self::optionsArray() as $optionArray) {
            $option = new DefaultInput($optionArray['name'], $optionArray['label']);

            $optionRecipe = new InputComponentRecipe(
                inputGroup: new SoleInputGroup,
                inputValue: $optionArray['value'] ?? $optionArray['name'],
                selectable: true,
                useParentValue: true,
                labelAsInputContent: true,
                disableBag: (new DefaultDisableBag)
                    ->setDisableWrapper()
                    ->setDisableDefaultNameAttribute(),
                themeBag: (new DefaultThemeBag)
                    ->setInputTheme([]),
                componentBag: (new DefaultComponentBag)
                    ->setInputType(FluxComponentEnum::OPTION)
            );

            $option->setRecipe($optionRecipe);

            $options[] = $option;
        }

        return CrudAssistant::make($options);
    }
}




