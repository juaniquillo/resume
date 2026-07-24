<?php

namespace App\Cruds\Schema\Profiles\Renderers;

use App\Cruds\Schema\Profiles\ProfilesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

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
