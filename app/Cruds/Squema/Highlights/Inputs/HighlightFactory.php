<?php

namespace App\Cruds\Squema\Highlights\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;

class HighlightFactory
{
    public static function make(): InputInterface
    {
        $input =  new DefaultInput();

        

        return $input;
    }
}
