<?php

namespace Database\Factories;

use App\Cruds\Schema\Languages\LanguagesCrud;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = LanguagesCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
