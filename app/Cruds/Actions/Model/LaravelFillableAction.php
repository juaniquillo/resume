<?php

namespace App\Cruds\Actions\Model;

use App\Support\Helpers;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

class LaravelFillableAction extends Action implements ActionInterface
{
    /**
     * Execute action on input.
     */
    public function execute(InputCollectionInterface|InputInterface $input): void
    {
        /** @var LaravelFillableRecipe|null */
        $recipe = $input->getRecipe($this->getIdentifier());

        $output = $this->getOutput();
        $name = $input->getName();

        if ($recipe) {
            $recipeName = $recipe->name ?? null;

            if ($recipeName) {
                $output->push($recipeName);

                return;
            }

            $callback = $recipe->callback ?? null;
            if (Helpers::isClosure($callback)) {
                $recipeCallbackName = $callback($input);
                $output->push($recipeCallbackName);

                return;
            }

        }

        $output->push($name);
    }
}
