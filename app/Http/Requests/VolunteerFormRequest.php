<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Volunteers\VolunteersCrud;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class VolunteerFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->crud = VolunteersCrud::build()->make();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->crud->execute(
            new LaravelValidationRulesAction
        )->toArray();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->crud->execute(
            new LaravelValidationMessagesAction
        )->toArray();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->crud->execute(
            new LaravelValidationLabelsAction
        )->toArray();
    }
}
