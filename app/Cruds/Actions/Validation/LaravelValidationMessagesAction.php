<?php

declare(strict_types=1);

namespace App\Cruds\Actions\Validation;

use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;

/**
 * Laravel validation messages action.
 */
class LaravelValidationMessagesAction extends Action implements ActionInterface
{
    public function execute(InputCollection|InputInterface|\IteratorAggregate $input)
    {
        /** @var LaravelValidationMessagesRecipe|null $recipe */
        $recipe = $input->getRecipe(static::class);
        $output = $this->getOutput();
        $name = $input->getName();

        if ($recipe) {
            $messages = $recipe->messages;
            $name = $recipe->name ?? $input->getName();

            if (\is_string($messages)) {
                $output->$name = $messages;

                return $output;
            }

            if (\is_callable($messages)) {
                $messages($output, $input);

                return $output;
            }

            foreach ($messages as $keyMessage => $message) {
                $output->$name = $message;
            }
        }

        return $output;
    }
}



