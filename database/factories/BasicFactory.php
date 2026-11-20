<?php

namespace Database\Factories;

use App\Cruds\Schema\Basics\BasicsCrud;
use App\Models\Basic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<Basic>
 */
class BasicFactory extends Factory
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
