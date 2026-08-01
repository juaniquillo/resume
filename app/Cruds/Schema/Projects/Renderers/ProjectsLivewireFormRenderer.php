<?php

namespace App\Cruds\Schema\Projects\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Projects\ProjectsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ProjectsLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ProjectsCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, ProjectsCrud::getLivewireGroup());

        $description = $inputs['description'] ?? null;

        if ($description) {
            $inputs['description'] = $crud->spanFullContainer([
                $description,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
