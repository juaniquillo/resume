<?php

namespace App\Cruds\Concerns;

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
