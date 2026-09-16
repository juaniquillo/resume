<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Skills\SkillsCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SkillsBuilder
{
    public function handle(User $user): ?array
    {
        $skills = $user->resumeSkills();
        if ($skills->isEmpty()) {
            return null;
        }

        return $skills->map(function (Model $skill) {
            return SkillsCrud::build()->make()->execute(new ModelToExportAction($skill))->toArray();
        })->toArray();
    }
}
