<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Education\EducationCrud;
use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EducationBuilder
{
    public function handle(User $user): ?array
    {
        $education = $user->resumeEducation();
        if ($education->isEmpty()) {
            return null;
        }

        return $education->map(function (Model $edu) {
            /** @var Education $edu */
            $eduArray = EducationCrud::build()->make()->execute(new ModelToExportAction($edu))->toArray();
            $eduArray['courses'] = $edu->courses->pluck('course')->toArray();

            return $eduArray;
        })->toArray();
    }
}
