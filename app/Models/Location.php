<?php

namespace App\Models;

use App\Models\Concerns\Uuidable;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $basic_id
 * @property-read string|null $address
 * @property-read string|null $postalCode
 * @property-read string $city
 * @property-read string $countryCode
 * @property-read string $region
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Basic $basics
 */
#[Guarded([])]
class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory,
        Uuidable;

    public function basics(): BelongsTo
    {
        return $this->belongsTo(Basic::class);
    }
}
