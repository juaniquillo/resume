<?php

namespace Database\Factories;

use App\Cruds\Actions\Model\LaravelFactoryAction;
use App\Cruds\Squema\Certificates\CertificatesCrud;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crud = CertificatesCrud::build();

        return $crud->make()->execute(
            new LaravelFactoryAction
        )->toArray();
    }
}
