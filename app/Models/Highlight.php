<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $highlightable_type
 * @property-read int $highlightable_id
 * @property-read string $highlight
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Model $highlightable
 */
#[Guarded([])]
class Highlight extends Model
{
    use HasFactory, InvalidatesResumeCache;

    public function highlightable()
    {
        return $this->morphTo();
    }

    public function resolveResumeUserId(): ?int
    {
        /** @var mixed $parent */
        $parent = $this->highlightable;

        return (int) ($parent->user_id ?? null);
    }
}
