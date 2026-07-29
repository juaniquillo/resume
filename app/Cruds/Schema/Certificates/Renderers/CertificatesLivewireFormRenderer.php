<?php

namespace App\Cruds\Schema\Certificates\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Certificates\CertificatesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class CertificatesLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var CertificatesCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, CertificatesCrud::getLivewireGroup());

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
