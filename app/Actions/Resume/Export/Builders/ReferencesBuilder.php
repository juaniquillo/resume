<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\References\ReferencesCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReferencesBuilder
{
    public function handle(User $user): ?array
    {
        $references = $user->resumeReferences();
        if ($references->isEmpty()) {
            return null;
        }

        return $references->map(function (Model $ref) {
            return ReferencesCrud::build()->make()->execute(new ModelToExportAction($ref))->toArray();
        })->toArray();
    }
}
