<?php

namespace App\Cruds\Schema\Profiles\Renderers;

use App\Cruds\Schema\Profiles\ProfilesCrud;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

final class ProfilesFormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderFull(ProfilesCrud $crud, array $fullSpanInputs): BackendComponent|CompoundComponent
    {
        return $crud->formFullSpanInputs($fullSpanInputs);
    }
}
