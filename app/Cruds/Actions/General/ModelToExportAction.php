<?php

namespace App\Cruds\Actions\General;

use Illuminate\Database\Eloquent\Model;
use IteratorAggregate;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\InputCollection;

class ModelToExportAction extends Action implements ActionInterface
{
    public function __construct(
        private Model $model
    ) {}

    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        if ($input instanceof InputCollection || $input instanceof IteratorAggregate) {
            foreach ($input as $item) {
                $this->execute($item);
            }

            return $this->getOutput();
        }

        /** @var DataContainer $output */
        $output = $this->getOutput();

        /** @var ModelToExportRecipe|null $recipe */
        $recipe = $input->getRecipe(ModelToExportAction::class);
        $inputName = $input->getName();

        if (! $recipe) {
            return $output;
        }

        $value = $this->model->{$inputName};

        if ($recipe->callback) {
            $value = ($recipe->callback)($value, $this->model);
        }

        $exportKey = $recipe->key ?? $inputName;

        $output->set($exportKey, $value);

        return $output;
    }
}
