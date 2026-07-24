<?php

namespace App\Cruds\Schema\Highlights\Renderers;

use App\Cruds\Schema\Highlights\HighlightsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class HighlightsFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderNarrow(HighlightsCrud $crud): BackendComponent|CompoundComponent
    {
        return $crud->composeForm($crud->inputsArray(), [
            'forms' => 'one-column',
        ]);
    }

    public function renderFull(HighlightsCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
