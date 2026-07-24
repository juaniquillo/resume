<?php

namespace App\Cruds\Actions\General;

use App\Cruds\Helpers\DateHelpers;
use Illuminate\Database\Eloquent\Model;
use IteratorAggregate;
use Juaniquillo\CrudAssistant\Concerns\IsAction;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;

class FormatDateAction implements ActionInterface
{
    use IsAction;

    public function __construct(
        private Model $model,
        private bool $onlyDates = false,
    ) {}

    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        /** @var ?FormatDateRecipe $recipe */
        $recipe = $input->getRecipe(self::getIdentifier());
        $isDate = $recipe?->isDate;
        $output = $this->getOutput();
        $name = $input->getName();
        $value = $this->model->{$name};

        if ($recipe && $isDate) {
            $output->set($name, DateHelpers::formatDateOutput($value));
        } elseif (! $this->onlyDates) {
            $output->set($name, $value);
        }

        return $output;

    }
}



