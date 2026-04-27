<?php

namespace App\Models;

use App\Models\Concerns\HasHighlights;
use App\Models\Concerns\Uuidable;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read Carbon $start_date
 * @property-read Carbon $end_date
 * @property-read string|null $url
 * @property-read string|null $description
 * @property-read string $user_id
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
#[Guarded([])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory,
        HasHighlights,
        Uuidable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
