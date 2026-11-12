<?php

namespace App\Cruds\Contracts;

use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

interface FormRenderer
{
    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent;
}
