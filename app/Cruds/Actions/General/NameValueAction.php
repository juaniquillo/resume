<?php

namespace App\Cruds\Actions\General;

use App\Support\Helpers;
use Closure;
use IteratorAggregate;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\InputCollection;

class NameValueAction extends Action implements ActionInterface
{
    /** @var array<int, Closure(?string):(?string)> */
    private array $modifiers = [];

    public function __construct(
        /** @var array<string, mixed> $values */
        private array $values
    ) {}

    /**
     * @param  Closure(?string):(?string)  $modifier
     */
    public function setModifier(Closure $modifier): static
    {
        $this->modifiers[] = $modifier;

        return $this;
    }

    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        if ($input instanceof InputCollection || $input instanceof IteratorAggregate) {
            foreach ($input as $item) {
                $this->execute($item);
            }

            return $this->getOutput();
        }

        /** @var DataContainer $output */
        $output = $this->getOutput();

        /** @var NameValueRecipe|null $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());
        $values = $this->values;
        $inputName = $input->getName();

        if (! $recipe) {
            if (! array_key_exists($inputName, $values)) {
                return $output;
            }

            $value = $values[$inputName];
            $output->set($inputName, $value);

            return $output;
        }

        $names = $this->resolveNames($recipe, $input);

        /** @var Closure(array $values):mixed */
        $callback = $recipe->callback ?? null;

        if (Helpers::isClosure($callback)) {
            $value = $callback($values);
            $output->set($inputName, $value);

            return $output;
        }

        if ($recipe->value !== null) {
            $value = Helpers::isClosure($recipe->value) ? ($recipe->value)($values) : $recipe->value;
            $output->set($inputName, $value);

            return $output;
        }

        $found = false;
        foreach ($names as $name) {
            if (array_key_exists($name, $values)) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            if ($recipe->default !== null) {
                $output->set($inputName, $recipe->default);
            }

            return $output;
        }

        $value = $this->resolveValue($names, $values);

        foreach ($this->modifiers as $modifier) {
            $value = $modifier($value);
        }

        $output->set($inputName, $value);

        return $output;
    }

    public function resolveNames(NameValueRecipe $recipe, InputInterface $input): array
    {
        $names = $recipe->useLabelAsName ? $input->getLabel() : ($recipe->name ?? $input->getName());

        if (is_string($names)) {
            return [$names];
        }

        return $names;
    }

    public function resolveValue(array $names, array $values): mixed
    {
        foreach ($names as $name) {
            if (array_key_exists($name, $values)) {
                return $values[$name];
            }
        }

        return null;
    }
}
