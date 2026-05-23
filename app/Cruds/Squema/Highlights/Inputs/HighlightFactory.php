<?php

namespace App\Cruds\Squema\Highlights\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\TableHelpers;
use BackedEnum;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultHookBag;
use Juaniquillo\InputComponentAction\Contracts\ErrorManager;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Groups\InputErrorGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class HighlightFactory
{
    const NAME = 'highlights';

    const LABEL = 'Highlights';

    public static function make(): InputInterface
    {
        $input = new DefaultInput('highlight', 'Highlight');

        self::form($input);
        self::validation($input);
        self::table($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'min:3',
                'max:1000',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setInputGroup(new InputErrorGroup)
                ->setValueAsInputContent(true)
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::TEXTAREA)
                        ->setErrorComponent(function ($type, $manager) {
                            return FormHelpers::errorAlertComponent($manager);
                        })
                )
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'badge' => 'required',
                        ])
                )
                ->setHookBag(
                    (new DefaultHookBag)
                        ->setErrorHook(function (BackendComponent|ContentComponent|CompoundComponent $component, InputInterface $input, string|BackedEnum $type, ValueManager $valueManager, ErrorManager $errorManager) {

                            /** @var InputComponentRecipe $recipe */
                            $recipe = $input->getRecipe(InputComponentAction::getIdentifier());
                            $error = $errorManager->resolve($input, $recipe);

                            // if error
                            if ($error) {
                                // prepend error svg icon
                                $component->prependContent(
                                    FormHelpers::errorIcon(),
                                );
                            }

                            return $component;
                        })
                )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function ($value) {
                    if ($value === null) {
                        return TableHelpers::emptyValue();
                    }

                    return TableHelpers::nl2br($value);
                }
            )
        );
    }
}
