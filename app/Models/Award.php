<?php

namespace App\Models;

use App\Models\Concerns\Uuidable;
use Database\Factories\AwardFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $title
 * @property-read string $awarder
 * @property-read string|null $summary
 * @property-read string $user_id
 * @property-read Carbon $awarded_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
#[Guarded([])]
class Award extends Model
{
    /** @use HasFactory<AwardFactory> */
    use HasFactory, Uuidable;
}
