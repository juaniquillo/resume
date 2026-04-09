<?php

namespace App\Cruds\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\InputCollection;

interface CrudInterface
{
    public function inputsArray(): array;

    public function make(?array $inputs = null): InputCollection;

    public function form(?array $inputs = null): BackendComponent|CompoundComponent;

    public function inputs(?array $inputs = null): array;

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent;

    public function setModel(Model $model): static;

    public function getModel(): ?Model;

    public function setValues(array $values): static;

    public function getValues(): array;

    public function setErrors(array $errors): static;

    public function getErrors(): array;
}
