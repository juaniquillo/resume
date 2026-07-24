<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use App\Models\ResumeExport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeExport>
 */
class ResumeExportFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $crud = ResumeExportCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}


