<?php

namespace App\Livewire\Concerns;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use Illuminate\Support\Facades\Validator;
use Juaniquillo\CrudAssistant\InputCollection;

trait IsLivewireForm
{
    public array $formErrors = [];

    protected function validateForm(InputCollection $crud, array $formValues)
    {
        $validator = Validator::make(
            $formValues,
            $crud->execute(
                new LaravelValidationRulesAction($formValues)
            )->toArray(),
            [],
            $crud->execute(
                new LaravelValidationLabelsAction
            )->toArray()
        );

        if ($validator->fails()) {
            $this->formErrors = $validator->errors()->toArray();
        }

        $validator->validate();

        return $validator;
    }
}



