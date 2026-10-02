<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Works\WorksCrud;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Model;

class WorkBuilder
{
    public function handle(User $user): ?array
    {
        $work = $user->resumeWorks();
        if ($work->isEmpty()) {
            return null;
        }

        return $work->map(function (Model $work) {
            /** @var Work $work */
            $workArray = WorksCrud::build()->make()->execute(new ModelToExportAction($work))->toArray();
            $workArray['highlights'] = $work->highlights->pluck('highlight')->toArray();

            return $workArray;
        })->toArray();
    }
}
