<?php

namespace App\Cruds\Schema\Skills\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Skills\SkillsCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class SkillsLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var SkillsCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, SkillsCrud::getLivewireGroup());

        $keywords = $inputs['keywords'] ?? null;

        if ($keywords) {
            $inputs['keywords'] = $crud->spanFullContainer([
                $keywords,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
