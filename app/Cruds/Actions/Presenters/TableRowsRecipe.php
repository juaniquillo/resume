<?php

namespace App\Cruds\Actions\Presenters;

use BackedEnum;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class TableRowsRecipe implements RecipeInterface
{
    use IsRecipe;

    public function __construct(
        public readonly ?string $label = null,
        /** @var string|Closure(?string $value, Model $model):(string|BackendComponent|CompoundComponent)|null $value */
        public readonly string|Closure|null $value = null,
        public readonly ?ThemeManager $themeManager = null,
        public readonly array $themes = [],
        public readonly array $attributes = [],
        /** @var ?class-string<BackendComponent|CompoundComponent> */
        public readonly ?string $component = null,
        public readonly string|BackedEnum|null $type = null,
        /** @var ?Closure(string|BackendComponent|CompoundComponent|null $value, BackendComponent|CompoundComponent $component):(BackendComponent|CompoundComponent) $callback */
        public readonly ?Closure $callback = null,
    ) {}

    protected $action = TableRowsAction::class;
}
