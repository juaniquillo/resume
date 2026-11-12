<?php

namespace App\Cruds\Helpers;

use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

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
