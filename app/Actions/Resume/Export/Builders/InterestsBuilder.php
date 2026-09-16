<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Interests\InterestsCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InterestsBuilder
{
    public function handle(User $user): ?array
    {
        $interests = $user->resumeInterests();
        if ($interests->isEmpty()) {
            return null;
        }

        return $interests->map(function (Model $interest) {
            return InterestsCrud::build()->make()->execute(new ModelToExportAction($interest))->toArray();
        })->toArray();
    }
}
