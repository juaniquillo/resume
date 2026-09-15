<?php

namespace App\Cruds\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Managers\EnumResolverValueManager;
use BackedEnum;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultDisableBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Containers\InputComponentOutput;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;
use Juaniquillo\InputComponentAction\Contracts\ErrorManager;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Groups\NoWrapSoleInputGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Managers\DefaultErrorManager;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

trait HasHtmlForm
{
    private ?string $formAction = null;

    private string $formMethod = 'POST';

    private string $saveButtonLabel = 'Save';

    private array $saveButtonAttributes = [];

    /**
     * @return array<?InputInterface>
     */
    public function inputsArray(): array
    {
        return [];
    }

    public function setFormAction(string $action): static
    {
        $this->formAction = $action;

        return $this;
    }

    public function setFormMethod(string $method): static
    {
        $this->formMethod = strtoupper($method);

        return $this;
    }

    public function setSaveButtonLabel(string $saveButtonLabel): static
    {
        $this->saveButtonLabel = $saveButtonLabel;

        return $this;
    }

    public function setSaveButtonAttributes(array $attributes): static
    {
        $this->saveButtonAttributes = $attributes;

        return $this;
    }

    public function saveButton(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setTheme('cursor', 'pointer')
            ->setAttributes($this->saveButtonAttributes)
            ->setContent(__($this->saveButtonLabel));
    }

    public function form(): BackendComponent|CompoundComponent
    {
        return $this->composeForm($this->inputsArray());
    }

    /** @param array<int|string, string> $fullSpanInputs */
    public function formFullSpanInputs(array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        $inputs = self::inputsArray();

        foreach ($fullSpanInputs as $index => $name) {
            $input = $inputs[$name] ?? null;

            $inputs[$name] = $this->spanFullContainer([$input], $index);
        }

        return $this->composeForm(
            inputs: $inputs,
        );
    }

    public function inputs(?array $inputs = null): array
    {
        $inputs ??= self::inputsArray();

        $action = (new InputComponentAction($this->getValues(), $this->getErrors()))
            ->setDefaultInputGroup(NoWrapSoleInputGroup::class)
            ->setDefaultComponentBag($this->dashboardComponentBag());

        if ($this->getModel()) {
            $action->setModel($this->getModel());
        }

        $action->setValueManager($this->valueManager());
        $action->setErrorManager($this->errorManager());

        $output = $this->make($inputs)->execute($action);

        /** @var InputComponentOutput $output */
        $inputs = $output->inputs;

        return $inputs->toArray();
    }

    public function spanFullContainer(array $contents, int|string $index = 0): InputCollectionInterface
    {
        return CrudAssistant::make($contents)
            ->setName('span_full_container_'.$index)
            ->setRecipe(
                (new InputComponentRecipe)
                    ->setThemeBag(
                        (new DefaultThemeBag)
                            ->setWrapperTheme([
                                'forms' => 'column-span-full',
                            ])
                    )
            );
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'two-column',
        ];
    }

    public function dashboardComponentBag(): ComponentBag
    {
        return (new DefaultComponentBag)
            ->setInputType(FluxComponentEnum::TEXT_INPUT)
            ->setInputComponent(FluxBackendComponent::class);
    }

    public function valueManager(): ValueManager
    {
        return new EnumResolverValueManager;
    }

    public function errorManager(): ErrorManager
    {
        return new DefaultErrorManager;
    }

    public function composeForm(?array $inputs = null, ?array $themes = null): BackendComponent|CompoundComponent
    {
        $themes ??= $this->formThemes();

        $form = LocalThemeComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', $this->formAction)
            ->setAttribute('method', $this->formMethod)
            ->setAttribute('enctype', 'multipart/form-data')
            ->setThemes(themes: $themes)
            ->setContents(
                $this->inputs(inputs: $inputs)
            );

        $form->setContent(
            LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                ->setTheme('forms', 'column-span-full')
                ->setContent(
                    $this->saveButton()
                )
        );

        return $form;
    }

    /**
     * @param  array<int|string, InputInterface>  $inputs
     * @param  array<int|string, class-string<ActionInterface>>  $onlyFor
     */
    public function fieldsetWrap(array $inputs, string|int $key, string $legend, array $wrapperAttributes = [], array $onlyFor = []): InputInterface
    {
        $fieldset = new InputCollection("fieldset_wrap_{$key}");

        $legendInput = (new DefaultInput("fieldset_legend_{$key}", $legend))
            ->setRecipe(
                (new InputComponentRecipe)
                    ->setInputGroup(new NoWrapSoleInputGroup)
                    ->setComponentBag(
                        (new DefaultComponentBag)
                            ->setInputComponent(
                                function (BackedEnum|string $type, ThemeManager $manager) use ($legend) {
                                    return (new FluxBackendComponent(FluxComponentEnum::LEGEND, $manager))
                                        ->setContent($legend);
                                }
                            )
                    )
            );

        $fieldset->setType(FormHelpers::FORM_WRAPPER_TYPE)
            ->onlyFor($onlyFor)
            ->setRecipe(
                (new InputComponentRecipe)
                    ->setThemeBag(
                        (new DefaultThemeBag)
                            ->setWrapperTheme([
                                'forms' => [
                                    'column-span-full',
                                    'one-column',
                                ],
                            ])
                    )
                    ->setAttributeBag(
                        (new DefaultAttributeBag)
                            ->setWrapperAttributes($wrapperAttributes)
                    )

            );

        $fieldset->setInputs([
            'legend' => $legendInput,
            'inputs' => CrudAssistant::make($inputs)
                ->setRecipe(
                    (new InputComponentRecipe)
                        ->setThemeBag(
                            (new DefaultThemeBag)
                                ->setWrapperTheme([
                                    'forms' => [
                                        'one-column',

                                    ],
                                ])
                        )
                ),
        ]);

        return $fieldset;
    }

    public function separator(int|string $key): InputInterface
    {
        $separator = new DefaultInput("fieldset_wrap_{$key}");

        $separator->onlyFor([
            InputComponentAction::class,
        ]);

        $separator->setType(FormHelpers::IGNORE_LIVEWIRE_BINDINGS)
            ->setRecipe(
                (new InputComponentRecipe)
                    ->setDisableBag(
                        (new DefaultDisableBag)
                            ->setDisableDefaultForAttribute()
                            ->setDisableDefaultNameAttribute()
                    )
                    ->setComponentBag(
                        (new DefaultComponentBag)
                            ->setInputType(FluxComponentEnum::SEPARATOR)
                    )
            );

        return $separator;
    }
}
