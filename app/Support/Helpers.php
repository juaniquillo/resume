<?php

namespace App\Support;

class Helpers
{
    public static function isClosure($callback)
    {
        return is_object($callback) && ($callback instanceof \Closure);
    }
}
