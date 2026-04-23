<?php

namespace App\Models;

use App\Models\Concerns\Uuidable;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string $date
 * @property-read string|null $url
 * @property-read string $user_id
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 */
#[Guarded([])]
class Certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory,
        Uuidable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
