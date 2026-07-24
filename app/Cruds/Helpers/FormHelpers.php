<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

class FormHelpers
{
    public static function errorAlertComponent(ThemeManager $manager): BackendComponent|CompoundComponent
    {
        return (new MainBackendComponent(ComponentEnum::DIV, $manager))
            ->setAttributes([
                'role' => 'alert',
                'aria-live' => 'polite',
                'aria-atomic' => 'true',
                'data-flux-error' => '',
                'class' => 'mt-3 text-sm font-medium text-red-500 dark:text-red-400',
            ]);
    }

    public static function errorIcon(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make('icon.exclamation-triangle')
            ->setAttributes([
                'variant' => 'solid',
                'class' => 'size-4',
            ])
            ->setTheme('display', 'inline-block');
    }

    public static function dateFormatOutput(): Closure
    {
        return function (InputInterface $input, array $values, ?object $model) {
            $name = $input->getName();

            if (! $model) {
                return null;
            }

            $value = ($model instanceof Model)
                ? $model->getAttribute($name)
                : ($model->{$name} ?? null);

            if ($value instanceof \DateTimeInterface) {
                return DateHelpers::formatDateOutput($value);
            }

            return null;
        };
    }

    public static function convertEmptyStringToNull(array $data): array
    {
        /** Brings back null for empty strings for Livewire forms */
        return array_map(fn ($value) => $value === '' ? null : $value, $data);
    }
}
