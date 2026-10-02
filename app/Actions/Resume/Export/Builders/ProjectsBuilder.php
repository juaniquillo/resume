<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Projects\ProjectsCrud;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProjectsBuilder
{
    public function handle(User $user): ?array
    {
        $projects = $user->resumeProjects();
        if ($projects->isEmpty()) {
            return null;
        }

        return $projects->map(function (Model $project) {
            /** @var Project $project */
            $projectArray = ProjectsCrud::build()->make()->execute(new ModelToExportAction($project))->toArray();
            $projectArray['highlights'] = $project->highlights->pluck('highlight')->toArray();

            return $projectArray;
        })->toArray();
    }
}
