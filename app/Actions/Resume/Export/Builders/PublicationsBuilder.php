<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Publications\PublicationsCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PublicationsBuilder
{
    public function handle(User $user): ?array
    {
        $publications = $user->resumePublications();
        if ($publications->isEmpty()) {
            return null;
        }

        return $publications->map(function (Model $pub) {
            return PublicationsCrud::build()->make()->execute(new ModelToExportAction($pub))->toArray();
        })->toArray();
    }
}
