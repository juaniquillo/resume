<?php

namespace App\Http\Requests\Resume;

use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use Illuminate\Foundation\Http\FormRequest;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

class ResumeExportFormRequest extends FormRequest
{
    private ?InputCollectionInterface $crud = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->crud = ResumeExportCrud::build()->make();
    }

    public function rules(): array
    {
        return $this->crud->execute(
            new LaravelValidationRulesAction
        )->toArray();
    }
}
