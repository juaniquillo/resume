<?php

declare(strict_types=1);

namespace App\Cruds\Actions\Validation;

use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\DataContainerInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;

/**
 * Laravel validation rules action.
 */
class LaravelValidationRulesAction extends Action implements ActionInterface
{
    public function __construct(
        protected array $requestArray = [],
        protected $model = null
    ) {}

    public function execute(InputCollection|InputInterface|\IteratorAggregate $input)
    {
        $this->executeOne($input);
    
        $subElements = $input->getSubElements();

        if ($subElements) {
            foreach ($subElements as $key => $subElement) {
                $this->executeOne($subElement);
            }
        }
        
        return  $this->getOutput();
    }

    public function executeOne(InputCollection|InputInterface|\IteratorAggregate $input)
    {
        $output = $this->getOutput();

        $name = $input->getName();

        /** @var LaravelValidationRulesRecipe|null $recipe */
        $recipe = $input->getRecipe(self::getIdentifier());

        if (! $recipe) {
            return $output;
        }

        $rules = $recipe->rules;

        if ($rules) {

            if (\is_callable($rules)) {
                $output->add(
                    $rules($input, $this->requestArray, $this->model)
                );
            } else {
                $output->$name = $rules;
            }
        }

        return $output;
    }
}
