<?php

namespace App\Cruds\Helpers;

use Carbon\CarbonImmutable;

class DateHelpers
{
    public static function formatDateOutput(?CarbonImmutable $carbon): ?string
    {
        return $carbon?->format('Y-m');
    }
}
