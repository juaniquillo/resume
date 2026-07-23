<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use App\Models\Concerns\Uuidable;
use Database\Factories\CoverLetterFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read int $user_id
 * @property-read string $content
 */
#[Guarded([])]
class CoverLetter extends Model
{
    /** @use HasFactory<CoverLetterFactory> */
    use HasFactory,
        InvalidatesResumeCache,
        Uuidable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
