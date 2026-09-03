<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Publication\StorePublication;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Publications\PublicationsCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class PublicationsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['publications'])) {
            return;
        }

        $publicationCrud = PublicationsCrud::build();
        $publicationInputs = $publicationCrud->make();
        $publicationRules = $publicationInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['publications'] as $pubData) {
            $mapped = $publicationInputs->execute(new NameValueAction($pubData))
                ->toArray();

            $validated = $this->validate($mapped, $publicationRules);

            (new StorePublication($validated, $user))->handle();
        }
    }
}
