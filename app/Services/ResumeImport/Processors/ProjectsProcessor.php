<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Project\StoreHighlight as StoreProjectHighlight;
use App\Actions\Resume\Project\StoreProject;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Projects\ProjectsCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class ProjectsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['projects'])) {
            return;
        }

        $projectCrud = ProjectsCrud::build();
        $projectInputs = $projectCrud->make();
        $projectRules = $projectInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['projects'] as $projectData) {
            $mapped = $projectInputs->execute(new NameValueAction($projectData))
                ->toArray();

            $validated = $this->validate($mapped, $projectRules);

            $project = (new StoreProject($validated, $user))->handle();
            if (isset($projectData['highlights'])) {
                foreach ($projectData['highlights'] as $highlight) {
                    (new StoreProjectHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $project))->handle();
                }
            }
        }
    }
}
