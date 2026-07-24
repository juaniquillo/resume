<?php

namespace App\Cruds\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

interface CrudInterface
{
    /**
     * @return array<?InputInterface>
     */
    public function inputsArray(): array;

    public function make(?array $inputs = null): InputCollectionInterface;

    public function setModel(Model $model): static;

    public function getModel(): ?Model;

    public function setValues(array $values): static;

    public function getValues(): array;

    public function setErrors(array $errors): static;

    public function getErrors(): array;
}
