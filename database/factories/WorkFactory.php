<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Work>
 */
class WorkFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = BasicsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
