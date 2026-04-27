<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;

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
}
