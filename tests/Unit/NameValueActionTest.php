<?php

use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\General\NameValueRecipe;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;

test('it resolves values from name', function () {
    $input = new DefaultInput('name', 'Name');
    $action = new NameValueAction(['name' => 'John Doe']);

    $result = $action->execute($input);

    expect($result->get('name'))->toBe('John Doe');
});

test('it resolves values from custom recipe names', function () {
    $input = new DefaultInput('name', 'Name');
    $input->setRecipe(new NameValueRecipe(name: ['company', 'full_name']));

    $action = new NameValueAction(['company' => 'Tech Corp']);
    $result = $action->execute($input);
    expect($result->get('name'))->toBe('Tech Corp');

    $action = new NameValueAction(['full_name' => 'Jane Doe']);
    $result = $action->execute($input);
    expect($result->get('name'))->toBe('Jane Doe');
});

test('it handles collections', function () {
    $inputs = [
        'first_name' => new DefaultInput('first_name', 'First Name'),
        'last_name' => new DefaultInput('last_name', 'Last Name'),
    ];
    $collection = CrudAssistant::make($inputs);

    $action = new NameValueAction([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $result = $action->execute($collection);

    expect($result->toArray())->toBe([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
});

test('it handles callbacks in recipe', function () {
    $input = new DefaultInput('full_name', 'Full Name');
    $input->setRecipe(new NameValueRecipe(
        callback: fn ($values) => ($values['first'] ?? '').' '.($values['last'] ?? '')
    ));

    $action = new NameValueAction(['first' => 'John', 'last' => 'Doe']);
    $result = $action->execute($input);

    expect($result->get('full_name'))->toBe('John Doe');
});

test('it handles explicit values in recipe', function () {
    $input = new DefaultInput('type', 'Type');
    $input->setRecipe(new NameValueRecipe(value: 'Person'));

    $action = new NameValueAction([]);
    $result = $action->execute($input);

    expect($result->get('type'))->toBe('Person');
});
