<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\References\ReferencesCrud;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class ReferenceFormRequest extends FormRequest
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
        $this->crud = ReferencesCrud::build()->make();

        if ($this->has('keywords') && is_string($this->keywords)) {
            $this->merge([
                'keywords' => array_filter(array_map('trim', explode(',', $this->keywords))),
            ]);
        }
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

    /**  @return array<string, string> */
    public function messages()
    {
        return $this->crud->execute(
            new LaravelValidationMessagesAction
        )->toArray();
    }

    /** @return array<string, string> */
    public function attributes()
    {
        return $this->crud->execute(
            new LaravelValidationLabelsAction
        )->toArray();
    }
}
