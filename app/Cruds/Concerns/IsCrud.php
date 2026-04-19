<?php

namespace App\Cruds\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Containers\InputComponentOutput;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;
use Juaniquillo\InputComponentAction\Groups\NoWrapSoleInputGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

trait IsCrud
{
    private ?string $formAction = null;

    public function inputsArray(): array
    {
        return [];
    }

    public function setFormAction(string $action): static
    {
        $this->formAction = $action;

        return $this;
    }

    public function make(?array $inputs = null): InputCollectionInterface
    {
        return CrudAssistant::make($inputs ?? $this->inputsArray());
    }

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setContent($label);
    }

    public function form(?array $inputs = null): BackendComponent|CompoundComponent
    {
        return LocalThemeComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', $this->formAction)
            ->setThemes($this->formThemes())
            ->setContents(
                $this->inputs(
                    inputs: $inputs,
                )
            )
            ->setContent(
                LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                    ->setTheme('forms', 'column-span-full')
                    ->setContent(
                        $this->saveButton()
                    )
            );
    }

    public function inputs(?array $inputs = null): array
    {
        $action = (new InputComponentAction($this->getValues(), $this->getErrors()))
            ->setDefaultInputGroup(NoWrapSoleInputGroup::class)
            ->setDefaultComponentBag($this->dashboardComponentBag());

        if ($this->getModel()) {
            $action->setModel($this->getModel());
        }

        $output = $this->make($inputs)->execute($action);

        /** @var InputComponentOutput $output */
        $inputs = $output->inputs;

        return $inputs->toArray();
    }

    public function spanFullContainer(array $contents): InputCollectionInterface
    {
        return CrudAssistant::make($contents)
            ->setName('span_full_container')
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

    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setValues(array $values): static
    {
        $this->values = $values;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model ?? null;
    }
}
