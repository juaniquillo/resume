<?php

namespace App\Actions\Resume\Import;

use App\Cruds\Helpers\FormHelpers;
use App\Models\ResumeImport;

class UpdateResumeImport
{
    public function __construct(
        private array $data,
        private ResumeImport $import
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);
        $name = $data['name'] ?? null;

        return $this->import->update([
            'name' => $name,
        ]);
    }
}
