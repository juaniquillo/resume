<?php

namespace App\Components\ThirdParty\Flux;

enum FluxComponentEnum: string
{
    // general
    case CARD = 'card';
    case HEADING = 'heading';
    case TEXT = 'text';
    case BUTTON = 'button';
    case ICON = 'icon';

    // forms
    case LABEL = 'label';
    case TEXT_INPUT = 'input';
    case TEXT_FILE = 'file';
    case TEXTAREA = 'textarea';

    // tables
    case TABLE = 'table';
    case THEAD = 'table.columns';
    case TH = 'table.column';
    case TBODY = 'table.rows';
    case TR = 'table.row';
    case TD = 'table.cell';

}
