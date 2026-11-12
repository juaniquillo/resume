<?php

namespace App\Cruds\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

interface CrudTable
{
    public function makeTable(Collection|LengthAwarePaginator $collection): BackendComponent|CompoundComponent|null;
}
