<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Locations\LocationsCrud;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crud = LocationsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();

    }
}
