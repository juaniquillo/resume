<?php

namespace Database\Factories;

use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Juaniquillo\LaravelModelActions\Actions\LaravelFactoryAction;

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
