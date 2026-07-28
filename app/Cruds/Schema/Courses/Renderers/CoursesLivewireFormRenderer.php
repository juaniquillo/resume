<?php

namespace App\Cruds\Schema\Courses\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Courses\CoursesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class CoursesLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var CoursesCrud $crud */
        $inputs = $crud->inputsArray();
        $this->addLivewireAttributes($inputs, CoursesCrud::getLivewireGroup());

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'one-column']
        );
    }
}
