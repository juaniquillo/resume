<?php

namespace App\Cruds\Actions\Model;

use Closure;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;

class LaravelFillableRecipe implements RecipeInterface
{
    use IsRecipe;

    public function __construct(
        public readonly ?string $name = null,
        /** @var Closure(InputInterface $input):string|null */
        public readonly ?Closure $callback = null,
    ) {
        $this->action = LaravelFillableAction::class;
    }

}
