<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Schema\Interests\InterestsCrud;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Interest>
 */
class InterestFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = InterestsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
