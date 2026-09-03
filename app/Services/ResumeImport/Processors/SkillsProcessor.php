<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Skill\StoreSkill;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Skills\SkillsCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;
use App\Support\RequestUtils;

class SkillsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['skills'])) {
            return;
        }

        $skillCrud = SkillsCrud::build();
        $skillInputs = $skillCrud->make();
        $skillRules = $skillInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['skills'] as $skillData) {
            $mapped = $skillInputs->execute(new NameValueAction($skillData))
                ->toArray();

            if (isset($mapped['keywords'])) {
                $mapped['keywords'] = RequestUtils::commaSeparatedToArray($mapped['keywords']);
            }

            $validated = $this->validate($mapped, $skillRules);

            (new StoreSkill($validated, $user))->handle();
        }
    }
}
