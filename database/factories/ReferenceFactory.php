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
            'keywords' => $this->faker->words(3),
            'user_id' => User::factory(),
        ];
    }
}
