<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Languages\LanguagesCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LanguagesBuilder
{
    public function handle(User $user): ?array
    {
        $languages = $user->resumeLanguages();
        if ($languages->isEmpty()) {
            return null;
        }

        return $languages->map(function (Model $lang) {
            return LanguagesCrud::build()->make()->execute(new ModelToExportAction($lang))->toArray();
        })->toArray();
    }
}
