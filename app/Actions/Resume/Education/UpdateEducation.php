<?php

namespace App\Actions\Resume\Education;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Education;

class UpdateEducation
{
    public function __construct(
        private array $data,
        private Education $education
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->education->update($data);
    }
}
