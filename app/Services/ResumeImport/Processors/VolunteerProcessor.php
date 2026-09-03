<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Volunteer\StoreHighlight;
use App\Actions\Resume\Volunteer\StoreVolunteer;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Volunteers\VolunteersCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class VolunteerProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['volunteer'])) {
            return;
        }

        $volunteerCrud = VolunteersCrud::build();
        $volunteerInputs = $volunteerCrud->make();
        $volunteerRules = $volunteerInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['volunteer'] as $volunteerData) {
            $mapped = $volunteerInputs->execute(new NameValueAction($volunteerData))
                ->toArray();

            $validated = $this->validate($mapped, $volunteerRules);

            $volunteer = (new StoreVolunteer($validated, $user))->handle();
            if (isset($volunteerData['highlights'])) {
                foreach ($volunteerData['highlights'] as $highlight) {
                    (new StoreHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $volunteer))->handle();
                }
            }
        }
    }
}
