<?php

namespace App\Cruds\Actions\Presenters;

use App\Support\Helpers;
use BackedEnum;
use Closure;
use Illuminate\Database\Eloquent\Model;
use IteratorAggregate;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\InputCollection;

class TableRowsAction extends Action implements ActionInterface
{
    public function __construct(
        private Model $model,
        private ThemeManager $themeManager = new DefaultThemeManager,
        private array $themes = [],
        private array $attributes = [],
        /** @var class-string<BackendComponent|CompoundComponent> */
        private string $component = MainBackendComponent::class,
        private string|BackedEnum $type = ComponentEnum::TD,
        /** @var array<string, TableRowsRecipe> $extraCells */
        private array $extraCells = [],
    ) {
        $this->output == new DataContainer;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function setExtraCell(string $identifier, TableRowsRecipe $recipe)
    {
        $this->extraCells[$identifier] = $recipe;

        return $this;
    }

    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        $model = $this->getModel();
        $output = $this->getOutput();
        $name = $input->getName();
        $label = $input->getLabel() ?? $input->getName();

        $value = $model->{$name} ?? null;

        /** @var TableRowsRecipe $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());

        $resolvedValue = $this->resolveValue($value, $recipe);

        $output->set(
            $label,
            $this->resolveCellComponent($resolvedValue, $recipe)
        );

        return $output;

    }

    public function resolveValue(?string $value = null, TableRowsRecipe|RecipeInterface|null $recipe = new TableRowsRecipe): string|BackendComponent|CompoundComponent|null
    {
        /** @var string|Closure(?string $value):(BackendComponent|CompoundComponent)|null $recipeValue */
        $recipeValue = $recipe->value ?? null;

        if (Helpers::isClosure($recipeValue)) {
            return $recipeValue($value);
        }

        if ($recipeValue) {
            return $recipeValue;
        }

        return $value;
    }

    public function resolveCellComponent(string|BackendComponent|CompoundComponent|null $value = null, TableRowsRecipe|RecipeInterface|null $recipe = new TableRowsRecipe): BackendComponent|CompoundComponent
    {
        /** @var ?Closure(string|BackendComponent|CompoundComponent|null $value, BackendComponent|CompoundComponent $component):(BackendComponent|CompoundComponent) $callback */
        $callback = $recipe->callback ?? null;

        $componentClass = $recipe->component ?? $this->component;
        $componentType = $recipe->type ?? $this->type;
        $manager = $recipe->themeManager ?? $this->themeManager;
        $themes = $recipe->themes ?? $this->themes;
        $attributes = $recipe->attributes ?? $this->attributes;

        $component = new $componentClass($componentType, $manager);
        $component->setAttributes($attributes)
            ->setContent($value);

        if ($themes) {
            $component->setThemes($themes);
        }

        if (Helpers::isClosure($callback)) {
            return $callback($value, $component);
        }

        return $component;
    }

    public function cleanup(): static
    {
        foreach ($this->extraCells as $name => $cell) {

        }

        return $this;
    }
}
