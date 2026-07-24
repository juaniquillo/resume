<?php

namespace App\Cruds\Schema\Locations\Renderers;

use App\Cruds\Schema\Locations\LocationsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LocationsFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderFull(LocationsCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
