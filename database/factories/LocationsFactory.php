<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Locations\LocationsCrud;
use App\Models\Locations;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Locations>
 */
class LocationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crud = LocationsCrud::make();

        return $crud->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
