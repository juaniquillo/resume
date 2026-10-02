<?php

namespace App\Cruds\Actions\General;

use Juaniquillo\CrudAssistant\Concerns\IsAction;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;

class FilterUnsetValuesAction implements ActionInterface
{
    use IsAction;

    public function __construct(
        private array $values
    ) {}

    public function execute(InputCollection|InputInterface|\IteratorAggregate $input)
    {
        $output = $this->getOutput();
        $name = $input->getName();

        $value = $this->values[$name] ?? null;

        if ($value !== null) {
            $output->set($name, $value);
        }

        return $output;
    }
}
