<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Schema\Education\EducationCrud;
use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

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
