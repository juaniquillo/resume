<?php

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $basic_id
 * @property-read string $network
 * @property-read string $username
 * @property-read string $url
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;
}
