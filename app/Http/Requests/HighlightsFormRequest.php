<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Highlights\HighlightsCrud;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class HighlightsFormRequest extends FormRequest
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
        $this->crud = HighlightsCrud::build()->make();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->crud->execute(new LaravelValidationRulesAction)->toArray();
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
