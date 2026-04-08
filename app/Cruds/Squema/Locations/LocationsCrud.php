<?php

namespace App\Cruds\Squema\Locations;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Locations\Inputs\AddressFactory;
use App\Cruds\Squema\Locations\Inputs\BasicsFactory;
use App\Cruds\Squema\Locations\Inputs\CityFactory;
use App\Cruds\Squema\Locations\Inputs\CountryCodeFactory;
use App\Cruds\Squema\Locations\Inputs\PostalCodeFactory;
use App\Cruds\Squema\Locations\Inputs\RegionFactory;

class LocationsCrud implements CrudInterface
{
    use IsCrud;

    public static function inputsArray(): array
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

    public static function formAction(): string
    {
        return '';
    }

}
