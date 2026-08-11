<?php

namespace App\Cruds\Concerns;

use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\LivewireHelpers;
use Juaniquillo\InputComponentAction\InputComponentAction;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

trait HasLivewireFormAttributes
{
    /**
     * @param  array<string, mixed>  $inputs
     */
    protected function addLivewireAttributes(array $inputs, string $livewireGroup): void
    {
        foreach ($inputs as $name => $input) {

            if ($input->getType() === FormHelpers::FORM_WRAPPER_TYPE) {
                foreach ($input->getSubElements() as $child) {
                    if($child->getType() === FormHelpers::FORM_WRAPPER_SEPARATOR_TYPE) {
                        continue;
                    }
                    
                    $this->addLivewireAttributes([$child->getName() => $child], $livewireGroup);
                }
            }

            /** @var InputComponentRecipe|null $recipe */
            $recipe = $input->getRecipe(InputComponentAction::getIdentifier());

            if ($recipe instanceof InputComponentRecipe && $recipe->getAttributeBag() !== null) {
                $attributes = LivewireHelpers::getLivewireAttributes($name, $livewireGroup);
                $attributeBag = $recipe->getAttributeBag();

                $currentAttributes = $attributeBag->getInputAttributes();
                $attributeBag->setInputAttributes(array_merge($currentAttributes, $attributes));
            }
        }
    }
}
