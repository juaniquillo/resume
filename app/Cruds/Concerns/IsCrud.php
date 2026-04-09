<?php

namespace App\Cruds\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Squema\Basics\BasicsCrud;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\InputComponentAction\Containers\InputComponentOutput;
use Juaniquillo\InputComponentAction\Groups\NoWrapSoleInputGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;

trait IsCrud
{
    public static function inputsArray(): array
    {
        return [];
    }

    public static function formAction(): string
    {
        return '';
    }

    public static function make(?array $inputs = null): InputCollection
    {
        return CrudAssistant::make($inputs ?? self::inputsArray());
    }

    public static function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('type', 'submit')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setContent($label);
    }

    public static function form(?array $inputs = null, ?array $values = null, ?array $errors = null, ?Model $model = null): BackendComponent|CompoundComponent
    {
        return LocalThemeComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', self::formAction())
            ->setThemes(self::formClasses())
            ->setContents(
                self::inputs(
                    inputs: $inputs,
                    values: $values,
                    errors: $errors,
                    model: $model
                )
            )
            ->setContent(
                LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                    ->setTheme('forms', 'column-span-full')
                    ->setContent(
                        self::saveButton()
                    )
            );
    }

    public static function inputs(?array $inputs = null, ?array $values = null, ?array $errors = null, ?Model $model = null): array
    {
        $output = self::make($inputs)->execute(
            (new InputComponentAction($values ?? [], $errors ?? []))
                ->setDefaultInputGroup(NoWrapSoleInputGroup::class)
                ->setDefaultComponentBag(BasicsCrud::dashboardComponentBag())
        );

        /** @var InputComponentOutput $output */
        $inputs = $output->inputs;

        return $inputs->toArray();
    }

    public static function formClasses(): array
    {
        return [
            'forms' => 'two-column',
        ];
    }
}
