<?php

namespace Database\Factories;

use App\Models\ResumeExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeExport>
 */
class ResumeExportFactory extends Factory
{
    protected $model = ResumeExport::class;

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
            'status' => 'completed',
        ];
    }
}
