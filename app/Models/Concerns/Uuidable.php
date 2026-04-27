<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait Uuidable
{
    public static function bootUuidable()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
