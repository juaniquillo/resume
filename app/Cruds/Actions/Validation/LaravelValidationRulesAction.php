<?php

declare(strict_types=1);

namespace App\Cruds\Actions\Validation;

use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

/**
 * Laravel validation rules action.
 */
class LaravelValidationRulesAction extends Action implements ActionInterface
{
    public function __construct(
        protected array $requestArray = [],
        protected $model = null
    ) {}

    public function execute(InputCollectionInterface|InputInterface $input): void
    {
        $this->executeOne($input);

        $subElements = $input->getSubElements();

        if ($subElements) {
            foreach ($subElements->getInputs() as $key => $subElement) {
                $this->executeOne($subElement);
            }
        }
    }

    public function executeOne(InputInterface $input): void
    {
        $output = $this->getOutput();

        $name = $input->getName();

        /** @var LaravelValidationRulesRecipe|null $recipe */
        $recipe = $input->getRecipe(self::getIdentifier());

        if (! $recipe) {
            return;
        }

        $rules = $recipe->rules;

        if ($rules) {

            if (\is_callable($rules)) {
                $output->merge(
                    $rules($input, $this->requestArray, $this->model)
                );
            } else {
                $output->$name = $rules;
            }
        }
    }
}
