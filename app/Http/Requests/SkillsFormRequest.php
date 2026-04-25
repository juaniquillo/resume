<?php

namespace App\Http\Requests;

use App\Cruds\Actions\Validation\LaravelValidationLabelsAction;
use App\Cruds\Actions\Validation\LaravelValidationMessagesAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Skills\Inputs\KeywordsFactory;
use App\Cruds\Squema\Skills\SkillsCrud;
use App\Support\RequestUtils;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class SkillsFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

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
        $this->crud = SkillsCrud::build()->make();

        /** @TODO Move to process request action */
        $keywordsName = KeywordsFactory::NAME;
        $keywords = $this->input($keywordsName);

        $processedKeywords = RequestUtils::commaSeparatedToArray($keywords);

        $this->merge([
            $keywordsName => $processedKeywords,
        ]);
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

    /** @return array<string, string> */
    public function messages()
    {
        return $this->crud->execute(new LaravelValidationMessagesAction)->toArray();
    }
}
