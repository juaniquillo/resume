<?php

namespace App\Support;

class RequestUtils
{
    public static function removePhoneSymbols(?string $phone): ?string
    {
        return $phone ? str_replace(['-', ' ', '(', ')'], '', $phone) : null;
    }

    public static function commaSeparatedToArray(?string $value): ?array
    {
        return is_string($value) && trim($value) !== ''
            ? array_filter(array_map('trim', explode(',', $value)))
            : null;
    }
}
