<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Education\EducationCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class EducationFormRenderer implements FormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var EducationCrud $crud */
        return $crud->formFullSpanInputs(['url']);
    }
}
