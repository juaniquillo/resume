<?php

use App\Cruds\Actions\General\NameValueAction;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;

test('it resolves values from name', function () {
    $input = new DefaultInput('name', 'Name');
    $action = new NameValueAction(['name' => 'John Doe']);

    $action->execute($input);

    expect($action->getOutput()->get('name'))->toBe('John Doe');
});
