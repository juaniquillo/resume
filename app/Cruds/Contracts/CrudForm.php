<?php

namespace App\Cruds\Contracts;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\InputComponentAction\Contracts\ErrorManager;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;

interface CrudForm
{
    /** @return array<?InputInterface> */
    public function inputsArray(): array;
    
    public function setFormAction(string $action): static;

    public function form(?array $inputs = null): BackendComponent|CompoundComponent;

    public function inputs(?array $inputs = null): array;

    public function saveButton(string $label = 'Save'): BackendComponent|CompoundComponent;

    public function spanFullContainer(array $contents): InputCollectionInterface;

    public function separator(int|string $key): InputInterface;

    public function fieldsetWrap(array $inputs, string|int $key, string $legend): InputInterface;

    public function formFullSpanInputs(array $fullSpanInputs): BackendComponent|CompoundComponent;

    public function valueManager(): ?ValueManager;

    public function errorManager(): ?ErrorManager;
}
