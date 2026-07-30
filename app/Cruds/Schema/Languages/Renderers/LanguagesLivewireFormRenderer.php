<?php

namespace App\Cruds\Schema\Languages\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Languages\LanguagesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LanguagesLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var LanguagesCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, LanguagesCrud::getLivewireGroup());

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
