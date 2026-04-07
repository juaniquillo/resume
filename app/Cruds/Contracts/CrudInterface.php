<?php

namespace App\Cruds\Contracts;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\InputCollection;

interface CrudInterface
{
    public static function make(): InputCollection;

    public static function form(array $inputs): MainBackendComponent;

    public static function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent;

}
