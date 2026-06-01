<?php

namespace Database\Factories;

use App\Models\GeneralOption;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GeneralOption>
 */
class GeneralOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'slug' => fake()->unique()->slug(),
            'theme' => 'default',
            'is_draft' => false,
            'hide_phone' => false,
            'hide_email' => false,
            'views' => 0,
        ];
    }
}
