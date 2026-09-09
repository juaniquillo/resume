<?php

namespace App\Cruds\Managers;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\InputComponentAction\Concerns\IsValueManager;
use Juaniquillo\InputComponentAction\Contracts\InputComponentRecipeInterface;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Utilities\Support;
use Stringable;

class SettingsValueManager implements ValueManager
{
    use IsValueManager;

    public function resolve(InputInterface $input, InputComponentRecipeInterface $recipe, bool $ignoreRecipeValue = false): Stringable|string|int|array|null
    {
        $values = $this->values;
        $model = $this->model;

        $name = $input->getName();

        if (array_key_exists(key: $name, array: $values)) {
            return $values[$name];
        }

        $recipeValue = $recipe->getInputValue();
        $modelValue = null;

        $settings = $this->model->settings ?? null;

        if ($settings && is_array($settings)) {
            $modelValue = $settings[$name] ?? null;
        }

        if (! $ignoreRecipeValue) {
            if (Support::isClosure($recipeValue)) {
                $recipeValueProcessed = $recipeValue($input, $values, $model);
            } else {
                $recipeValueProcessed = $recipeValue;
            }
        }

        return $recipeValueProcessed ?? $modelValue ?? null;
    }
}
