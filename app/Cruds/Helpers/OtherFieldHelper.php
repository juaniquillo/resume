<?php

namespace App\Cruds\Helpers;

use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationLabelsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationMessagesRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Groups\SoleInputGroup;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class OtherFieldHelper
{
    public static function wrapperInput(string $wrapperName): InputCollectionInterface
    {
        $collection = new InputCollection($wrapperName);

        $collection->setRecipe(
            new InputComponentRecipe(
                /** Overwrite theme manager */
                themeManager: new DefaultThemeManager,
                /** Add themes */
                themeBag: (new DefaultThemeBag)
                    ->setWrapperTheme([
                        'display' => 'grid',
                        'grid' => 'gap-md',
                    ]),
                attributeBag: (new DefaultAttributeBag)
                    ->setWrapperAttributes([
                        'x-data' => '{ selectedNetwork:  null }',
                    ])
            )
        );

        return $collection;
    }

    public static function otherNetworkInput(InputInterface $relatedInput, string $valueName, string $value): InputInterface
    {
        $name = 'other_network';
        $label = 'Other Network';

        $relatedName = $relatedInput->getName();

        $input = new DefaultInput($name, $label);

        $input->setRecipe(
            new InputComponentRecipe(
                /**
                 * For some reason does not work with
                 */
                inputGroup: new SoleInputGroup,
                attributeBag: (new DefaultAttributeBag)
                    ->setWrapperAttributes([
                        'x-cloak' => '',
                        'x-show' => $valueName." === '".$value."'",
                    ])
                    ->setInputAttributes([
                        'label' => $label,
                    ]),
            )
        );

        $input->setRecipe(
            new LaravelValidationRulesRecipe([
                'nullable',
                'string',
                'max:255',
                'required_if:'.$relatedName.','.$value,
            ])
        );

        $input->setRecipe(
            (new LaravelValidationRulesRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelValidationMessagesRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelValidationLabelsRecipe)->ignore()
        );

        $input->setRecipe(
            (new LaravelFactoryRecipe)->ignore()
        );

        $input->setRecipe(
            (new TableRowsRecipe)->ignore()
        );

        return $input;
    }
}
