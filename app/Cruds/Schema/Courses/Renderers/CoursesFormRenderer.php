<?php

namespace App\Cruds\Schema\Courses\Renderers;

use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\Courses\CoursesCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class CoursesFormRenderer implements FormRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var CoursesCrud $crud */
        return $crud->formFullSpanInputs(['course']);
    }
}
