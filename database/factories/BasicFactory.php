<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Models\Basic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Basic>
 */
class BasicFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = BasicsCrud::make();

        dd($crud->execute(
            new LaravelFactoryAction
        )->toArray());

        return $crud->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
