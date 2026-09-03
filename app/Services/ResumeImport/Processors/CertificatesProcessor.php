<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Certificate\StoreCertificate;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class CertificatesProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['certificates'])) {
            return;
        }

        $certificateCrud = CertificatesCrud::build();
        $certificateInputs = $certificateCrud->make();
        $certificateRules = $certificateInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['certificates'] as $certData) {
            $mapped = $certificateInputs->execute(new NameValueAction($certData))
                ->toArray();

            $validated = $this->validate($mapped, $certificateRules);

            (new StoreCertificate($validated, $user))->handle();
        }
    }
}
