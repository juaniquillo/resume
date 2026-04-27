<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Education\EducationCrud;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class EducationFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->crud = EducationCrud::build()->make();

    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return $this->crud->execute(new LaravelValidationRulesAction)->toArray();
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return $this->crud->execute(new LaravelValidationLabelsAction)->toArray();
    }
}
