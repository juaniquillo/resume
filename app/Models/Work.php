<?php

namespace App\Models;

use App\Models\Concerns\HasHighlights;
use App\Models\Concerns\InvalidatesResumeCache;
use App\Models\Concerns\Uuidable;
use App\Models\Contracts\HighlightModel;
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
 * @property-read string|null $summary
 * @property-read int $user_id
 * @property-read Carbon $starts_at
 * @property-read Carbon|null $ends_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read string|null $url
 * @property-read User $user
 * @property-read Collection<int, Highlight> $highlights
 */
#[Guarded([])]
class Work extends Model implements HighlightModel
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory,
        HasHighlights,
        InvalidatesResumeCache,
        Uuidable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }
}
