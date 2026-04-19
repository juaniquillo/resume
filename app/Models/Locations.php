<?php

namespace App\Models;

use Database\Factories\LocationsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $basic_id
 * @property-read string $address
 * @property-read string $postalCode
 * @property-read string $city
 * @property-read string $countryCode
 * @property-read string $region
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Locations extends Model
{
    /** @use HasFactory<LocationsFactory> */
    use HasFactory;
}
