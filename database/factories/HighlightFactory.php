<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Models\Highlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Highlight>
 */
class HighlightFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = HighlightsCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
