<?php

namespace App\Cruds\Actions\General;

use Closure;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class NameValueRecipe implements RecipeInterface
{
    use IsRecipe;

    protected $action = NameValueAction::class;

    public function __construct(
        public readonly string|array|null $name = null,
        public readonly string|Closure|null $value = null,
        public readonly mixed $default = null,
        public readonly bool $useLabelAsName = false,
        public readonly ?Closure $callback = null,
    ) {}
}
