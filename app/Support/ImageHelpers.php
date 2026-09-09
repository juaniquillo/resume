<?php

namespace App\Support;

class ImageHelpers
{
    public  static function imageUrl(string $uuid, ?int $timestamp = null) : string
    {
        return  route('image.serve', $uuid).'?v='.($timestamp ?? now()->timestamp);
    }
}
