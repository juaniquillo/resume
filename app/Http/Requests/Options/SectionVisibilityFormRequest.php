<?php

namespace App\Http\Requests\Options;

use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Options\SectionVisibilityCrud;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class SectionVisibilityFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->crud = SectionVisibilityCrud::build()->make();
    }

    public function rules(): array
    {
        return $this->crud->execute(
            new LaravelValidationRulesAction
        )->toArray();
    }
}
