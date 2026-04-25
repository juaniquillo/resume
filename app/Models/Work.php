<?php

namespace App\Models;

use App\Models\Concerns\HasHighlights;
use App\Models\Concerns\Uuidable;
use Database\Factories\WorkFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string $position
 * @property-read string $summary
 * @property-read string $user_id
 * @property-read Carbon $starts_at
 * @property-read Carbon $ends_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 * @property-read Collection<int, Highlight> $highlights
 */
#[Guarded([])]
class Work extends Model
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory,
        HasHighlights,
        Uuidable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
