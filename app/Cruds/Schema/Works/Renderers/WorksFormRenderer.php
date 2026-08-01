<?php

namespace App\Cruds\Schema\Works\Renderers;

use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Works\Inputs\SummaryFactory;
use App\Cruds\Schema\Works\WorksCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class WorksFormRenderer implements FormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var WorksCrud $crud */
        return $crud->formFullSpanInputs([SummaryFactory::NAME]);
    }
}
