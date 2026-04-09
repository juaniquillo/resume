<?php

namespace App\Cruds\Squema\Highlights;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Highlights\Inputs\HighlightFactory;

class HighlightsCrud implements CrudInterface
{
    use IsCrud;
    
    public static function inputsArray(): array
    {
        return [
            HighlightFactory::make(),
        ];
    }

    public static function formAction(): string
    {
        return '';
    }
}
