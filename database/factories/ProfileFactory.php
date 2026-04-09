<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crud = ProfilesCrud::make();

        return $crud->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
