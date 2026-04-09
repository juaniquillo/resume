<?php

namespace App\Cruds\Squema\Works\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;

class HighlightsFactory
{
    
    const NAME = 'highlights_works';

    const LABEL = 'Highlights';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);


        return $input;
    }

}
