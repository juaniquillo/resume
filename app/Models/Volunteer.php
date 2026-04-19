<?php

namespace App\Models;

use App\Models\Concerns\HasHighlights;
use App\Models\Concerns\Uuidable;
use Database\Factories\VolunteerFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $organization
 * @property-read string $position
 * @property-read string $url
 * @property-read string $summary
 * @property-read string $user_id
 * @property-read Carbon $starts_at
 * @property-read Carbon $ends_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Collection<int, Highlight> $highlights
 */
#[Guarded([])]
class Volunteer extends Model
{
    /** @use HasFactory<VolunteerFactory> */
    use HasFactory,
        HasHighlights,
        Uuidable;
}
