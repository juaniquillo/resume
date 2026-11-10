<?php

namespace App\Cruds\Actions\General;

use App\Support\Helpers;
use Closure;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;

class NameValueAction extends Action implements ActionInterface
{
    private ?string $globalDefault = null;

    public function __construct(
        /** @var array<string, mixed> $values */
        private array $values
    ) {}

    public function setGlobalDefault(?string $default): static
    {
        $this->globalDefault = $default;

        return $this;
    }

    public function execute(InputCollectionInterface|InputInterface $input): void
    {
        if ($input instanceof InputCollectionInterface) {
            foreach ($input as $item) {
                $this->execute($item);
            }

            return;
        }

        /** @var DataContainer $output */
        $output = $this->getOutput();

        /** @var NameValueRecipe|null $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());
        $values = $this->values;
        $inputName = $input->getName();

        if (! $recipe) {

            if (! array_key_exists($inputName, $values)) {

                if ($this->globalDefault !== null) {
                    $output->set($inputName, $this->globalDefault);
                }

                return;
            }

            $value = $values[$inputName];
            $output->set($inputName, $value);

            return;
        }

        $names = $this->resolveNames($recipe, $input);

        /** @var Closure(array $values):mixed */
        $callback = $recipe->callback ?? null;

        if (Helpers::isClosure($callback)) {
            $value = $callback($values);
            $output->set($inputName, $value);

            return;
        }

        if ($recipe->value !== null) {
            $value = Helpers::isClosure($recipe->value) ? ($recipe->value)($values) : $recipe->value;
            $output->set($inputName, $value);

            return;
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
            } elseif ($this->globalDefault !== null) {
                $output->set($inputName, $this->globalDefault);
            }

            return;
        }

        $value = $this->resolveValue($names, $values);

        $value = $this->modifiers($value, $input);

        $output->set($inputName, $value);

    }

    public function resolveNames(NameValueRecipe $recipe, InputInterface $input): array
    {
        $names = $recipe->useLabelAsName ? $input->getLabel() : ($recipe->name ?? $input->getName());

        if (\is_string($names)) {
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
