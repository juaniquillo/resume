<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Cruds\Schema\Education\EducationCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class EducationFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderFull(EducationCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
