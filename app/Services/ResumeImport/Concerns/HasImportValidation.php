<?php

namespace App\Services\ResumeImport\Concerns;

use Illuminate\Support\Facades\Validator;

trait HasImportValidation
{
    protected function validate(array $data, array $rules): array
    {
        return Validator::make($data, $rules)->validate();
    }
}
