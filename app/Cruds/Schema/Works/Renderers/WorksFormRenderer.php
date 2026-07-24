<?php

namespace App\Cruds\Schema\Works\Renderers;

use App\Cruds\Schema\Works\WorksCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class WorksFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderNarrow(WorksCrud $crud): BackendComponent|CompoundComponent
    {
        return $crud->composeForm($crud->inputsArray(), [
            'forms' => 'one-column',
        ]);
    }

    public function renderFull(WorksCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
