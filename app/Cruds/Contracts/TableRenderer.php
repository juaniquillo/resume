<?php

namespace App\Cruds\Contracts;

use App\Cruds\Actions\Presenters\TableRowsAction;

interface TableRenderer
{
    public function tableActionInstance(TableRowsAction $action): void;
}
