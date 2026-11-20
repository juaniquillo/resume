<?php

namespace Database\Factories;

use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Models\ResumeImport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

/**
 * @extends Factory<ResumeImport>
 */
class ResumeImportFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = ResumeImportCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
