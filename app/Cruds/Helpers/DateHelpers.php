<?php

namespace App\Cruds\Helpers;

use Carbon\CarbonImmutable;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

class DateHelpers
{
    public static function formatDateOutput(?CarbonImmutable $carbon): string|BackendComponent|CompoundComponent|null
    {
        if (!$carbon) {
            return TableHelpers::emptyValue();
        }

        return $carbon?->format('Y-m');
    }
}
