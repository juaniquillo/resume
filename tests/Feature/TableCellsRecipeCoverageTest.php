<?php

use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

test('every input rendered by table actions has a table cells recipe', function () {
    $missing = [];

    $check = function (InputCollectionInterface|InputInterface $input, string $path) use (&$check, &$missing) {
        $recipe = $input->getRecipe(TableCellsAction::class);

        if ($recipe && $recipe->isIgnored()) {
            return;
        }

        if (! $input->isFor(TableCellsAction::class)) {
            return;
        }

        if ($input instanceof InputCollectionInterface) {
            foreach ($input->getInputs() as $name => $child) {
                $check($child, $path.'/'.$name);
            }

            return;
        }

        if (! $recipe instanceof TableCellsRecipe) {
            $missing[] = $path.' ('.$input::class.')';
        }
    };

    foreach (glob(app_path('Cruds/Schema/*'), GLOB_ONLYDIR) as $schemaDir) {
        $renderers = array_filter(
            glob($schemaDir.'/Renderers/*TableRenderer.php') ?: [],
            fn ($file) => ! str_contains(basename($file), 'Livewire')
        );

        if ($renderers === []) {
            continue;
        }

        $schema = basename($schemaDir);

        foreach (glob($schemaDir.'/*Crud.php') as $crudFile) {
            $class = 'App\\Cruds\\Schema\\'.$schema.'\\'.basename($crudFile, '.php');

            foreach ($class::build()->inputsArray() as $name => $input) {
                $check($input, $schema.'/'.$name);
            }
        }
    }

    expect($missing)->toBeEmpty();
});
