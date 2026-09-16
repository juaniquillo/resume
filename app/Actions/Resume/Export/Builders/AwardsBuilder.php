<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Awards\AwardsCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AwardsBuilder
{
    public function handle(User $user): ?array
    {
        $awards = $user->resumeAwards();
        if ($awards->isEmpty()) {
            return null;
        }

        return $awards->map(function (Model $award) {
            return AwardsCrud::build()->make()->execute(new ModelToExportAction($award))->toArray();
        })->toArray();
    }
}
