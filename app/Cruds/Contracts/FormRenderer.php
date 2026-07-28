<?php

namespace App\Cruds\Contracts;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

interface FormRenderer
{
    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent;
}
