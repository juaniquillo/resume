<?php

namespace App\Cruds\Squema\ResumeExport\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\TableHelpers;
use App\Enums\ResumeTheme;
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

class ExportThemeSelectFactory
{
    public const NAME = 'theme';

    public const LABEL = 'Theme (PDF only)';

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
            new LaravelFactoryRecipe(callback: fn () => fake()->randomElement(ResumeTheme::cases())->value)
        );
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'nullable',
                'string',
                Rule::enum(ResumeTheme::class),
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
                        ])
                )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                label: 'Theme',
                value: function (Stringable|BackedEnum|string|array|null $value, Model $model) {

                    if ($value === null) {
                        return TableHelpers::emptyValue();
                    }

                    if ($value instanceof ResumeTheme) {
                        return $value->label();
                    }

                    return $value;
                }
            )
        );
    }

    public static function optionsArray(): array
    {
        $options = [];

        foreach (ResumeTheme::cases() as $value) {
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
