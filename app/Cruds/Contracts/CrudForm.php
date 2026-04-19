<?php

namespace App\Cruds\Contracts;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;

interface CrudForm
{
    public function setFormAction(string $action): static;

    public function form(?array $inputs = null): BackendComponent|CompoundComponent;

    public function inputs(?array $inputs = null): array;

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent;

    public function spanFullContainer(array $contents): InputCollectionInterface;
}
