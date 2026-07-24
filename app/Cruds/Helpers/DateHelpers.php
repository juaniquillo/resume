<?php

namespace App\Cruds\Helpers;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

class DateHelpers
{
    public static function formatDateOutput(?\DateTimeInterface $date): string|BackendComponent|CompoundComponent
    {
        if (! $date) {
            return TableHelpers::emptyValue();
        }

        return $date->format('Y-m');
    }
}



