<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Award\StoreAward;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Awards\AwardsCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class AwardsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['awards'])) {
            return;
        }

        $awardCrud = AwardsCrud::build();
        $awardInputs = $awardCrud->make();
        $awardRules = $awardInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['awards'] as $awardData) {
            $mapped = $awardInputs->execute(new NameValueAction($awardData))
                ->toArray();

            $validated = $this->validate($mapped, $awardRules);

            (new StoreAward($validated, $user))->handle();
        }
    }
}
