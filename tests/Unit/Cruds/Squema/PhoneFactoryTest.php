<?php

use App\Cruds\Squema\Basics\Inputs\PhoneFactory;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\InputComponentAction\Contracts\InputComponentRecipeInterface;
use Juaniquillo\InputComponentAction\InputComponentAction;

test('it creates a phone input with correct configuration', function () {
    $input = PhoneFactory::make();

    expect($input)->toBeInstanceOf(InputInterface::class);
    expect($input->getName())->toBe(PhoneFactory::NAME);
    expect($input->getLabel())->toBe(PhoneFactory::LABEL);

    /** @var InputComponentRecipeInterface $recipe */
    $recipe = $input->getRecipe(InputComponentAction::class);
    $attributes = $recipe->getAttributeBag()->getInputAttributes();

    expect($attributes['label'])->toBe(PhoneFactory::LABEL);
    expect($attributes['type'])->toBe('tel');
});

// test('it can be used to create a form input using the input component action', function () {

//     $crud = CrudAssistant::make([
//         PhoneFactory::make(),
//     ]);

//     $output = $crud->execute(
//         new InputComponentAction
//     );

// });
