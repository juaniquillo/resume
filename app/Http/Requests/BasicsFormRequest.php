<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Basics\Inputs\PhoneFactory;
use App\Support\RequestUtils;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\InputCollection;

class BasicsFormRequest extends FormRequest
{
    private ?InputCollection $crud = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->crud = BasicsCrud::make();

        $phone = $this->input(PhoneFactory::NAME);

        $this->merge([
            PhoneFactory::NAME => RequestUtils::removePhoneSymbols($phone),
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
