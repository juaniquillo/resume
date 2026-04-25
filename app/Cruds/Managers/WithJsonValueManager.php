<?php

namespace App\Cruds\Managers;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\InputComponentAction\Contracts\InputComponentRecipeInterface;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Utilities\Support;

/**
 * Priorities:
 * 1 - In $values array
 * 2 - In $recipe inputValue
 * 3 - In $model property
 */
final class WithJsonValueManager implements ValueManager
{
    private array $values = [];

    private ?object $model = null;

    public function setValues(array $values): static
    {
        $this->values = $values;

        return $this;
    }

    public function setModel(?object $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function resolve(InputInterface $input, InputComponentRecipeInterface $recipe, bool $ignoreRecipeValue = false): string|int|array|null
    {
        $values = $this->values;
        $model = $this->model;

        $name = $input->getName();

        if (array_key_exists(key: $name, array: $values)) {
            $value = $values[$name];

            $plainValue = is_array($value) ? trim(implode(', ', $value)) : $value;
        }

        $recipeValue = $recipe->getInputValue();
        $modelValue = null;

        $modelValueRaw = $model->{$name} ?? null;
        $modelValue = is_array($modelValueRaw) ? trim(implode(', ', $modelValueRaw)) : $modelValueRaw;

        $recipeValueProcessed = null;

        if (! $ignoreRecipeValue) {
            if (Support::isClosure($recipeValue)) {
                $recipeValueProcessed = $recipeValue($input, $values, $model);
            } else {
                $recipeValueProcessed = $recipeValue;
            }
        }

        return $recipeValueProcessed ?? $plainValue ?? $modelValue ?? null;
    }
}
