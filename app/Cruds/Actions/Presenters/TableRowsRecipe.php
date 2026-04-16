<?php

namespace App\Cruds\Actions\Presenters;

use BackedEnum;
use Closure;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class TableRowsRecipe implements RecipeInterface
{
    use IsRecipe;

    public function __construct(
        public readonly string|Closure $value,
        public readonly ThemeManager $themeManager = new DefaultThemeManager(),
        public readonly array $cellThemes = [],
        public readonly array $cellAttributes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        public readonly string $cellComponent = MainBackendComponent::class,
        public readonly string|BackedEnum $componentCellType = ComponentEnum::TD,
        public readonly ?Closure $callback = null,
    ) 
    {}

    protected $action = TableRowsAction::class;
}

