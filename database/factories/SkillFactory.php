<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Skills\SkillsCrud;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crud = SkillsCrud::build();

        return array_merge(
            $crud->make()->execute(new LaravelFactoryAction)->toArray(),
            ['keywords' => []]
        );
    }
}
