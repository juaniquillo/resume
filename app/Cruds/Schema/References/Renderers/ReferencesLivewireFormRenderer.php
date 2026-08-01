<?php

namespace App\Cruds\Schema\References\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\References\ReferencesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ReferencesLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ReferencesCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, ReferencesCrud::getLivewireGroup());

        $reference = $inputs['reference'] ?? null;

        if ($reference) {
            $inputs['reference'] = $crud->spanFullContainer([
                $reference,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
