<?php

namespace Database\Factories;

use App\Cruds\Schema\Projects\ProjectsCrud;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = ProjectsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
