<?php

namespace App\Cruds\Concerns;

use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\LivewireHelpers;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\CrudAssistant\Support\InputHelper;
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

            if ($input->getType() === FormHelpers::IGNORE_LIVEWIRE_BINDINGS) {
                continue;
            }

            if ($input->getType() === FormHelpers::FORM_WRAPPER_TYPE && InputHelper::isCollection($input)) {

                /** @var InputCollection $input */
                $children = $input->getInputs();
                if (! $children) {
                    continue;
                }
                foreach ($children as $child) {
                    $this->addLivewireAttributes([$child->getName() => $child], $livewireGroup);
                }
            }

            /** @var InputComponentRecipe|null $recipe */
            $recipe = $input->getRecipe(InputComponentAction::getIdentifier());

            if ($recipe instanceof InputComponentRecipe && $recipe->getAttributeBag() !== null) {
                $attributes = LivewireHelpers::getLivewireAttributes($name, $livewireGroup);
                $attributeBag = $recipe->getAttributeBag();

                $currentAttributes = $attributeBag->getInputAttributes() ?? [];
                $attributeBag->setInputAttributes(array_merge($currentAttributes, $attributes));
            }
        }
    }
}
