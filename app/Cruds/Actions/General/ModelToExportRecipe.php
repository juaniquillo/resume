<?php

namespace App\Cruds\Actions\General;

use Closure;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class ModelToExportRecipe implements RecipeInterface
{
    use IsRecipe;

    protected $action = ModelToExportAction::class;

    public function __construct(
        public readonly ?string $key = null,
        public readonly ?Closure $callback = null,
    ) {}
}
