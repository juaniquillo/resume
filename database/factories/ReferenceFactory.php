<?php

namespace Database\Factories;

use App\Models\Reference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reference>
 */
class ReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'reference' => $this->faker->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}
