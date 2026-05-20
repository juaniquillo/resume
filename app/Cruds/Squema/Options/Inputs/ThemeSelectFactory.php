<?php

namespace App\Cruds\Squema\Options\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Enums\ResumeTheme;
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

class ThemeSelectFactory
{
    public const NAME = 'theme';

    public const LABEL = 'Choose Resume Theme';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);

        $input->setSubElements(self::options());

        self::validation($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
                Rule::enum(ResumeTheme::class),
            ]))
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

        foreach (ResumeTheme::cases() as $key => $value) {
            $options[] = [
                'name' => $value->value,
                'label' => $value->label(),
            ];
        }

        return $options;
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
                    // reset default theme
                    // for the options
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
