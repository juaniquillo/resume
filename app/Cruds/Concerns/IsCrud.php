<?php

namespace App\Cruds\Concerns;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;

trait IsCrud
{
    public function inputsArray(): array
    {
        return [];
    }

    public function make(?array $inputs = null): InputCollectionInterface
    {
        return CrudAssistant::make($inputs ?? $this->inputsArray());
    }

    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setValues(array $values): static
    {
        $this->values = $values;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model ?? null;
    }
}
