<?php

namespace App\Cruds\Actions\Presenters;

use BackedEnum;
use Closure;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
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
        /** @var string|Closure(?string $value):(BackendComponent|CompoundComponent)|null $value */
        public readonly string|Closure|null $value = null,
        public readonly ThemeManager $themeManager = new DefaultThemeManager,
        public readonly array $themes = [],
        public readonly array $attributes = [],
        /** @var class-string<BackendComponent|CompoundComponent> */
        public readonly string $component = MainBackendComponent::class,
        public readonly string|BackedEnum $type = ComponentEnum::TD,
        /** @var ?Closure(string|BackendComponent|CompoundComponent|null $value, BackendComponent|CompoundComponent $component):(BackendComponent|CompoundComponent) $callback */
        public readonly ?Closure $callback = null,
    ) {}

    protected $action = TableRowsAction::class;
}
