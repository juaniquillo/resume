<?php

namespace App\Cruds\Actions\Model;

use App\Support\Helpers;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;

class LaravelFillableAction extends Action implements ActionInterface
{
    /**
     * Execute action on input.
     */
    public function execute($input)
    {
        $output = $this->getOutput();
        $name = $input->getName();
        $label = $input->getLabel();

        $recipe = $input->getRecipe($this->getIdentifier());

        if ($recipe) {
            $fillable = $recipe->fillable ?? null;

            if ($fillable) {
                $output[$name] = $fillable;

                return $output;
            }

            $callback = $recipe->callback ?? null;
            if (Helpers::isClosure($callback)) {
                $output[$name] = $callback($input);

                return $output;
            }

        }

        $output[$name] = $label;

        return $output;
    }
}
