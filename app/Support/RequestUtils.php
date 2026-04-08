<?php

namespace App\Support;

class RequestUtils
{
    public static function removePhoneSymbols(?string $phone): ?string
    {
        return $phone ? str_replace(['-', ' ', '(', ')'], '', $phone) : null;
    }
}
