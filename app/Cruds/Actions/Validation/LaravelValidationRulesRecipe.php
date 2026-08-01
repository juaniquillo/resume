<?php

declare(strict_types=1);

namespace App\Cruds\Actions\Validation;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;
use Juaniquillo\CrudAssistant\Input;

final class LaravelValidationRulesRecipe implements RecipeInterface
{
    use IsRecipe;

    /**
     * Recipe action
     *
     * @var string
     */
    protected $action = LaravelValidationRulesAction::class;

    /** @param array|Closure(Input, array, ?Model):array $rules */
    public function __construct(
        public readonly array|Closure $rules = []
    ) {}
}
