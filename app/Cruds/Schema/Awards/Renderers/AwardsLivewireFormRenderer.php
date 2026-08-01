<?php

namespace App\Cruds\Schema\Awards\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Awards\AwardsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class AwardsLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var AwardsCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, AwardsCrud::getLivewireGroup());

        $summary = $inputs['summary'] ?? null;

        if ($summary) {
            $inputs['summary'] = $crud->spanFullContainer([
                $summary,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
