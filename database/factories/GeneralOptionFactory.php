<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Models\GeneralOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GeneralOption>
 */
class GeneralOptionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = GeneralOptionsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}


