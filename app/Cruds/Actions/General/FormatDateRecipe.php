<?php

namespace App\Cruds\Actions\General;

use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class FormatDateRecipe implements RecipeInterface
{
    use IsRecipe;

    protected $action = FormatDateAction::class;

    public function __construct(
        public readonly bool $isDate = false,
    ) {}

}



