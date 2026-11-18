<?php

namespace App\Cruds\Contracts;

use Juaniquillo\TableComponentAction\TableCellsAction;

interface TableRenderer
{
    public function tableActionInstance(TableCellsAction $action): void;
}
