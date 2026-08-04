<?php

namespace App\Cruds\Schema\Profiles\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Profiles\ProfilesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ProfilesLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ProfilesCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, ProfilesCrud::getLivewireGroup());

        $url = $inputs['url'] ?? null;

        if ($url) {
            $inputs['url'] = $crud->spanFullContainer([
                $url,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
