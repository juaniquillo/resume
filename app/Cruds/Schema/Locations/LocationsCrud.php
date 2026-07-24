<?php

namespace App\Cruds\Schema\Locations;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Schema\Locations\Inputs\AddressFactory;
use App\Cruds\Schema\Locations\Inputs\BasicsFactory;
use App\Cruds\Schema\Locations\Inputs\CityFactory;
use App\Cruds\Schema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Schema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Schema\Locations\Inputs\RegionFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class LocationsCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'basics_location';

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
            'city' => CityFactory::make(),
            'country_code' => CountryCodeFactory::make(),
            'basics' => BasicsFactory::make(),
            'address' => AddressFactory::make(),
            'postal_code' => PostalCodeFactory::make(),
            'region' => RegionFactory::make(),
        ];
    }

    public function formWithInputsSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->formFullSpanInputs(['region']);
    }

    public static function getLivewireGroup(): string
    {
        return 'location';
    }
}




