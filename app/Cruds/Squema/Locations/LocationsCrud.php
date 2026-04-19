<?php

namespace App\Cruds\Squema\Locations;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Squema\Locations\Inputs\AddressFactory;
use App\Cruds\Squema\Locations\Inputs\BasicsFactory;
use App\Cruds\Squema\Locations\Inputs\CityFactory;
use App\Cruds\Squema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Squema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Squema\Locations\Inputs\RegionFactory;
use Illuminate\Database\Eloquent\Model;

final class LocationsCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public function inputsArray(): array
    {
        return [
            BasicsFactory::make(),
            AddressFactory::make(),
            PostalCodeFactory::make(),
            CityFactory::make(),
            CountryCodeFactory::make(),
            RegionFactory::make(),
        ];
    }

    public function formAction(): string
    {
        return '';
    }
}
