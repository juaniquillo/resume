<?php

namespace Database\Factories;

use App\Models\CoverLetter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoverLetter>
 */
class CoverLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'company' => $this->faker->optional()->company,
            'content' => $this->faker->paragraphs(3, true),
        ];
    }
}
