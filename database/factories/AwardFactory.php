<?php

namespace Database\Factories;

use App\Cruds\Schema\Awards\AwardsCrud;
use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<Award>
 */
class AwardFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = AwardsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
