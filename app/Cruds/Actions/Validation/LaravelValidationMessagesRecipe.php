<?php

declare(strict_types=1);

namespace App\Cruds\Actions\Validation;

use Closure;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class LaravelValidationMessagesRecipe implements RecipeInterface
{
    use IsRecipe;

    /**
     * Recipe action
     *
     * @var string
     */
    protected $action = LaravelValidationMessagesAction::class;

    public function __construct(
        public readonly string|array|Closure $messages,
        public ?string $name = null,
    ) {}
}



