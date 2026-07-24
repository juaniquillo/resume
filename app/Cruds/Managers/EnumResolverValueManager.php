<?php

namespace App\Cruds\Managers;

use BackedEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\InputComponentAction\Contracts\InputComponentRecipeInterface;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Utilities\Support;
use Stringable;

class EnumResolverValueManager implements ValueManager
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

    public function resolve(InputInterface $input, InputComponentRecipeInterface $recipe, bool $ignoreRecipeValue = false): Stringable|string|int|array|null
    {
        $values = $this->values;
        $model = $this->model;

        $name = $input->getName();
        $recipeValue = $recipe->getInputValue();

        $recipeValueProcessed = null;

        if (\array_key_exists(key: $name, array: $values)) {
            return $this->formatValue($values[$name]);
        }

        if (! $ignoreRecipeValue) {
            if (Support::isClosure($recipeValue)) {
                $recipeValueProcessed = $recipeValue($input, $values, $model);
            } else {
                $recipeValueProcessed = $recipeValue;
            }
        }

        $modelValueRaw = $model->{$name} ?? null;

        return $this->formatValue($recipeValueProcessed) ?? $this->formatValue($modelValueRaw) ?? null;
    }

    private function formatValue(mixed $value): Stringable|string|int|array|null
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if ($value instanceof Stringable) {
            return $value;
        }

        if (\is_array($value)) {
            return \trim(implode(', ', $value));
        }

        return $value;
    }
}
