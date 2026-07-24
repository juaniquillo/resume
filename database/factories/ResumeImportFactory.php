<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Models\ResumeImport;
use Illuminate\Database\Eloquent\Factories\Factory;

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


