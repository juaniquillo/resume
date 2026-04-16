<?php

namespace App\Cruds\Actions\Presenters;

use App\Support\Helpers;
use BackedEnum;
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
        private ThemeManager $themeManager = new DefaultThemeManager(),
        private array $themes = [],
        private array $attributes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        private string $component = MainBackendComponent::class,
        private string|BackedEnum $type = ComponentEnum::TD,
    ) 
    {
        $this->output == new DataContainer();
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

    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        $model =  $this->getModel();
        $output = $this->getOutput();
        $name = $input->getName();

        $value = $model->{$name} ?? null;

        /** @var TableRowsRecipe $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());

        $cellValue = $this->resolveValue($value, $recipe);

        $output->set(
            $name,
            $this->resolveCellComponent($recipe, $cellValue)
        );
        
    }

    public function resolveCellComponent(TableRowsRecipe|RecipeInterface $recipe, $value): BackendComponent|CompoundComponent
    {
        /** @var Closure($value): (BackendComponent|CompoundComponent)|null */
        $callback = $recipe->cellComponent ?? null;

        if (Helpers::isClosure($callback)) {
            return $callback($value);
        }

        $componentClass = $recipe->cellComponent ?? $this->component;
        $componentType = $recipe->componentType ?? $this->type;
        $manager = $recipe->themeManager ?? $this->themeManager;
        $themes = $recipe->cellThemes ?? $this->themes;

        $component = new $componentClass($componentType, $manager);
        $component ->setThemes($themes)
            ->setAttributes($this->attributes)
            ->setContent($value);
        
        return $component;
    }

    public function resolveValue(string $value, TableRowsRecipe|RecipeInterface $recipe)
    {
        $recipeValue = $recipe->value ?? null;

        if (Helpers::isClosure($recipeValue)) {
            return $recipeValue($value);
        }

        if($recipeValue) {
            return $recipeValue;
        }

        return $value;
    }


}
