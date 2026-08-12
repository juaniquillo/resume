<?php

namespace App\Cruds\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

interface TableRenderer
{
    public function renderSettings(Model $model): BackendComponent|CompoundComponent;

    public function renderExtraCells(): array;
}
