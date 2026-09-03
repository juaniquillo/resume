<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Work\StoreHighlight;
use App\Actions\Resume\Work\StoreWork;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Works\WorksCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class WorkProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['work'])) {
            return;
        }

        $workCrud = WorksCrud::build();
        $workInputs = $workCrud->make();
        $workRules = $workInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['work'] as $workData) {
            $mapped = $workInputs->execute(new NameValueAction($workData))
                ->toArray();

            $validated = $this->validate($mapped, $workRules);

            $work = (new StoreWork($validated, $user))->handle();
            if (isset($workData['highlights'])) {
                foreach ($workData['highlights'] as $highlight) {
                    (new StoreHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $work))->handle();
                }
            }
        }
    }
}
