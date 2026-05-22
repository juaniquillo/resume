<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $theme
 */
class Theme extends Model
{
    use InvalidatesResumeCache;

    protected $fillable = [
        'user_id',
        'theme',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function resolveResumeUserId(): ?int
    {
        return (int) $this->user_id;
    }
}
