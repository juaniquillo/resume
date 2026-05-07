<?php

namespace App\Actions\Resume\Certificate;

use App\Models\Certificate;

class UpdateCertificate
{
    public function __construct(
        private array $data,
        private Certificate $certificate
    ) {}

    public function handle(): bool
    {
        return $this->certificate->update($this->data);
    }
}
