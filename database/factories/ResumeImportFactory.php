<?php

namespace Database\Factories;

use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeImport>
 */
class ResumeImportFactory extends Factory
{
    protected $model = ResumeImport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'file_path' => $this->faker->filePath(),
            'file_name' => $this->faker->word().'.json',
            'status' => 'completed',
        ];
    }
}
