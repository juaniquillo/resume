<?php

namespace App\Cruds\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\InputCollection;

interface CrudInterface
{
    public static function inputsArray(): array;

    public static function make(?array $inputs = null): InputCollection;

    public static function form(?array $inputs = null, ?array $values = null, ?array $errors = null, ?Model $model = null): BackendComponent|CompoundComponent;

    public static function inputs(?array $inputs = null, ?array $values = null, ?array $errors = null, ?Model $model = null): array;

    public static function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent;
}
