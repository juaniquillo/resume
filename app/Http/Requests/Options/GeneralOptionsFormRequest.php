<?php

namespace App\Http\Requests\Options;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Options\GeneralOptionsCrud;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class GeneralOptionsFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->crud = GeneralOptionsCrud::build(model: $this->user())->make();
    }

    public function rules(): array
    {
        return $this->crud->execute(
            new LaravelValidationRulesAction
        )->toArray();
    }

    public function messages(): array
    {
        return $this->crud->execute(
            new LaravelValidationMessagesAction
        )->toArray();
    }

    public function attributes(): array
    {
        return $this->crud->execute(
            new LaravelValidationLabelsAction
        )->toArray();
    }
}
