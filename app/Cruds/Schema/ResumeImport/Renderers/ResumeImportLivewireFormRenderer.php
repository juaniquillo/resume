<?php

namespace App\Cruds\Schema\ResumeImport\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ResumeImportLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ResumeImportCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, ResumeImportCrud::getLivewireGroup());

        $fileInput = $inputs['json_file'] ?? null;

        if ($fileInput) {
            $inputs['json_file'] = $crud->spanFullContainer([
                $fileInput,
            ]);
        }

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
