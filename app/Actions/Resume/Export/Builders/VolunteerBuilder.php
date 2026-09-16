<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Volunteers\VolunteersCrud;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;

class VolunteerBuilder
{
    public function handle(User $user): ?array
    {
        $volunteer = $user->resumeVolunteers();
        if ($volunteer->isEmpty()) {
            return null;
        }

        return $volunteer->map(function (Model $volunteer) {
            /** @var Volunteer $volunteer */
            $volunteerArray = VolunteersCrud::build()->make()->execute(new ModelToExportAction($volunteer))->toArray();
            $volunteerArray['highlights'] = $volunteer->highlights->pluck('highlight')->toArray();

            return $volunteerArray;
        })->toArray();
    }
}
