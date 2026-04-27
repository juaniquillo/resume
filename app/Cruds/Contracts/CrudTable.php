<?php

namespace App\Cruds\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

interface CrudTable
{
    public function makeTable(Collection|LengthAwarePaginator $collection): BackendComponent|CompoundComponent|null;
}
