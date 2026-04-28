<?php

namespace App\Cruds\Squema\Locations;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Locations\Inputs\AddressFactory;
use App\Cruds\Squema\Locations\Inputs\BasicsFactory;
use App\Cruds\Squema\Locations\Inputs\CityFactory;
use App\Cruds\Squema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Squema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Squema\Locations\Inputs\RegionFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LocationsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
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
            'basics' => BasicsFactory::make(),
            'address' => AddressFactory::make(),
            'postal_code' => PostalCodeFactory::make(),
            'city' => CityFactory::make(),
            'country_code' => CountryCodeFactory::make(),
            'region' => RegionFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['region']);
    }
}
