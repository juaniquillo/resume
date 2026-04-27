<?php

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Models\Basic;
use App\Models\User;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;

it('can build a BasicsCrud instance', function () {
    $crud = BasicsCrud::build();

    expect($crud)->toBeInstanceOf(BasicsCrud::class);
});

it('returns a form component with textarea span full', function () {
    $crud = BasicsCrud::build();
    $component = $crud->formWithTextareaSpanFull();

    expect($component)->toBeInstanceOf(BackendComponent::class);
});

it('can build with values, errors and model', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->create([
        'user_id' => $user->id,
    ]);
    $values = ['name' => 'John Doe'];
    $errors = ['email' => ['The email field is required.']];

    $crud = BasicsCrud::build(
        values: $values,
        errors: $errors,
        model: $basic
    );

    expect($crud)->toBeInstanceOf(BasicsCrud::class);
});

it('renders the form with the expected inputs', function () {
    $crud = BasicsCrud::build();
    $component = $crud->formWithTextareaSpanFull();

    $view = $this->blade('{{ $form }}', ['form' => $component]);

    $view->assertSee('name="name"', false)
        ->assertSee('name="label"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="phone"', false)
        ->assertSee('name="url"', false)
        ->assertSee('name="image"', false)
        ->assertSee('name="summary"', false)
        ->assertSee('type="submit"', false);
});

it('renders values in the form component', function () {
    $values = ['name' => 'Juaniquillo'];
    $crud = BasicsCrud::build(values: $values);
    $component = $crud->formWithTextareaSpanFull();

    $view = $this->blade('{{ $form }}', ['form' => $component]);

    $view->assertSee('Juaniquillo');
});

it('renders errors in the form component', function () {
    $errors = ['name' => ['The name field is required.']];
    $crud = BasicsCrud::build(errors: $errors);
    $component = $crud->formWithTextareaSpanFull();

    $view = $this->blade('{{ $form }}', [
        'form' => $component,
        'errors' => $errors,
    ]);

    // Check for the error message or at least the invalid state
    $view->assertSee('data-invalid', false);
});

it('renders model values in the form component', function () {
    $user = User::factory()->create();
    $basic = Basic::factory()->create([
        'user_id' => $user->id,
        'name' => 'Model Name',
    ]);

    $crud = BasicsCrud::build(model: $basic);
    $component = $crud->formWithTextareaSpanFull();

    $view = $this->blade('{{ $form }}', ['form' => $component]);

    $view->assertSee('Model Name');
});
