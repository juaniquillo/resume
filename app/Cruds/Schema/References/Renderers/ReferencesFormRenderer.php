<?php

namespace App\Cruds\Schema\References\Renderers;

use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\References\ReferencesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ReferencesFormRenderer implements FormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ReferencesCrud $crud */
        return $crud->formWithTextareaSpanFull();
    }
}
