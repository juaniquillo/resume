<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Interest\StoreInterest;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Interests\InterestsCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;
use App\Support\RequestUtils;

class InterestsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['interests'])) {
            return;
        }

        $interestCrud = InterestsCrud::build();
        $interestInputs = $interestCrud->make();
        $interestRules = $interestInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['interests'] as $interestData) {
            $mapped = $interestInputs->execute(new NameValueAction($interestData))
                ->toArray();

            if (isset($mapped['keywords'])) {
                $mapped['keywords'] = RequestUtils::commaSeparatedToArray($mapped['keywords']);
            }

            $validated = $this->validate($mapped, $interestRules);

            (new StoreInterest($validated, $user))->handle();
        }
    }
}
