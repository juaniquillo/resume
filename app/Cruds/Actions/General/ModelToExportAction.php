<?php

namespace App\Cruds\Actions\General;

use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;

class ModelToExportAction extends Action implements ActionInterface
{
    public function __construct(
        private Model $model
    ) {}

    public function execute(InputCollectionInterface|InputInterface $input): void
    {
        if ($input instanceof InputCollectionInterface) {
            foreach ($input as $item) {
                $this->execute($item);
            }

            return;
        }

        /** @var DataContainer $output */
        $output = $this->getOutput();

        /** @var ModelToExportRecipe|null $recipe */
        $recipe = $input->getRecipe(ModelToExportAction::class);
        $inputName = $input->getName();

        $exportKey = $inputName;
        $value = $this->model->{$inputName};

        if ($recipe) {
            if ($recipe->callback) {
                $value = ($recipe->callback)($value, $this->model);
            }

            $exportKey = $recipe->key ?? $inputName;
        }

        $output->set($exportKey, $value);
    }
}
