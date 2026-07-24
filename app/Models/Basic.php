<?php

namespace App\Models;

use App\Actions\Resume\Basics\DeleteBasicsImage;
use App\Models\Concerns\InvalidatesResumeCache;
use App\Models\Concerns\Uuidable;
use Database\Factories\BasicFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string $label
 * @property-read string|null $image
 * @property-read string $email
 * @property-read string|null $phone
 * @property-read string|null $url
 * @property-read string|null $summary
 * @property-read string $user_id
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 * @property-read Location|null $location
 * @property-read Collection<int, Profile> $profiles
 */
#[Guarded([])]
class Basic extends Model
{
    /** @use HasFactory<BasicFactory> */
    use HasFactory,
        InvalidatesResumeCache,
        Uuidable;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($basic) {
            $basic->location?->delete();
            $basic->profiles->each->delete();
            (new DeleteBasicsImage($basic))->handle();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}



