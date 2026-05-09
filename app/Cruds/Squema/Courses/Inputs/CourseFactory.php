<?php

namespace App\Cruds\Squema\Courses\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Course;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
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

class CourseFactory
{
    const NAME = 'courses';

    const LABEL = 'Courses';

    public static function make(): InputInterface
    {
        $input = new DefaultInput('course', 'Course');

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
                value: function ($value, Model $model) {

                    /** @var Course $educationCourse */
                    $educationCourse = $model;
                    $modalContent = LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                        ->setContent($value)
                        ->setTheme('spacing', 'm-top-sm')
                        ->setTheme('text', 'nl2br');

                    return ComponentBuilder::make(ComponentEnum::COLLECTION)
                        ->setContent(Str::limit($value, 60))
                        ->setContent(
                            TableHelpers::tableModal(
                                id: $educationCourse->id,
                                content: $modalContent,
                                heading: CourseFactory::LABEL,
                                buttonLabel: 'view',
                                triggerType: 'ghost',
                            )
                        );

                }
            )
        );
    }
}
