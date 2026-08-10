<?php

namespace App\Cruds\Schema\Awards\Renderers;

use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Awards\AwardsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class AwardsFormRenderer implements FormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var AwardsCrud $crud */
        $inputs = $crud->inputsArray();
        $summary = $inputs['summary'] ?? null;

        if ($summary) {
            $inputs['summary'] = $crud->spanFullContainer([
                $summary,
            ]);
        }

        return $crud->form();
    }
}
