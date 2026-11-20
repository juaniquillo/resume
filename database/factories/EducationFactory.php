<?php

namespace Database\Factories;

use App\Cruds\Schema\Education\EducationCrud;
use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = EducationCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
