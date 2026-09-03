<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Reference\StoreReference;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\References\ReferencesCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class ReferencesProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['references'])) {
            return;
        }

        $referenceCrud = ReferencesCrud::build();
        $referenceInputs = $referenceCrud->make();
        $referenceRules = $referenceInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['references'] as $refData) {
            $mapped = $referenceInputs->execute(new NameValueAction($refData))
                ->toArray();

            $validated = $this->validate($mapped, $referenceRules);

            (new StoreReference($validated, $user))->handle();
        }
    }
}
