<?php

namespace App\Cruds\Actions\Model;

use Closure;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class LaravelFactoryRecipe implements RecipeInterface
{
    use IsRecipe;

    protected $action = LaravelFactoryAction::class;

    public function __construct(
        public readonly ?string $type = null,
        public readonly ?Closure $callback = null,
    ) {}

}
