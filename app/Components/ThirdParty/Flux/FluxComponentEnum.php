<?php

namespace App\Components\ThirdParty\Flux;

enum FluxComponentEnum: string
{
    case LABEL = 'label';
    case TEXT_INPUT = 'input';
    case TEXT_FILE = 'file';
    case TEXTAREA = 'textarea';
    case BUTTON = 'button';
}
