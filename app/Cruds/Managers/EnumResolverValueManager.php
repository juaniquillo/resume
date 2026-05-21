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
            return $values[$name];
        }

        if (! $ignoreRecipeValue) {
            if (Support::isClosure($recipeValue)) {
                $recipeValueProcessed = $recipeValue($input, $values, $model);
            } else {
                $recipeValueProcessed = $recipeValue;
            }
        }

        $modelValue = null;

        /** @var BackedEnum|Stringable|array|string|null $modelValueRaw */
        $modelValueRaw = $model->{$name} ?? null;

        if ($modelValueRaw instanceof BackedEnum) {
            $modelValue = $modelValueRaw->value;
        } elseif ($modelValueRaw instanceof Stringable) {
            $modelValue = $modelValueRaw->__toString();
        } else {
            $modelValue = \is_array($modelValueRaw) ? \trim(implode(', ', $modelValueRaw)) : $modelValueRaw;
        }

        return $recipeValueProcessed ?? $modelValue ?? null;
    }
}
