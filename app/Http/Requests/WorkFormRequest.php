<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Works\Inputs\UuidFactory;
use App\Cruds\Squema\Works\WorksCrud;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Juaniquillo\CrudAssistant\InputCollection;

class WorkFormRequest extends FormRequest
{
    private ?InputCollection $crud = null;
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation() 
    {
        $this->crud = WorksCrud::build()->make();

        $this->merge([
            UuidFactory::NAME => Str::uuid()->toString(),
        ]);
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
    public function messages()
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
    public function attributes()
    {
        return $this->crud->execute(
            new LaravelValidationLabelsAction
        )->toArray();
    }

}
