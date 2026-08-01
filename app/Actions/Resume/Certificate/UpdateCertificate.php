<?php

namespace App\Actions\Resume\Certificate;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Certificate;

class UpdateCertificate
{
    public function __construct(
        private array $data,
        private Certificate $certificate
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->certificate->update($data);
    }
}
