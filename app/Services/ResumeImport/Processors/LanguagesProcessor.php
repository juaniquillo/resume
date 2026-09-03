<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Language\StoreLanguage;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Languages\LanguagesCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class LanguagesProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['languages'])) {
            return;
        }

        $languageCrud = LanguagesCrud::build();
        $languageInputs = $languageCrud->make();
        $languageRules = $languageInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['languages'] as $langData) {
            $mapped = $languageInputs->execute(new NameValueAction($langData))
                ->toArray();

            $validated = $this->validate($mapped, $languageRules);

            (new StoreLanguage($validated, $user))->handle();
        }
    }
}
