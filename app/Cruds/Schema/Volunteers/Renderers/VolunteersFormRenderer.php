<?php

namespace App\Cruds\Schema\Volunteers\Renderers;

use App\Cruds\Schema\Volunteers\VolunteersCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class VolunteersFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderFull(VolunteersCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
