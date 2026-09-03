<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Education\StoreCourse;
use App\Actions\Resume\Education\StoreEducation;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Education\EducationCrud;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;

class EducationProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['education'])) {
            return;
        }

        $educationCrud = EducationCrud::build();
        $educationInputs = $educationCrud->make();
        $educationRules = $educationInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['education'] as $eduData) {
            $mapped = $educationInputs->execute(new NameValueAction($eduData))
                ->toArray();

            $validated = $this->validate($mapped, $educationRules);

            $education = (new StoreEducation($validated, $user))->handle();

            if (isset($eduData['courses'])) {
                foreach ($eduData['courses'] as $course) {
                    (new StoreCourse(['course' => is_array($course) ? ($course['course'] ?? '') : $course], $education))->handle();
                }
            }
        }
    }
}
