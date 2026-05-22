<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $user_id
 * @property-read string $slug
 * @property-read string $theme
 */
class GeneralOption extends Model
{
    use InvalidatesResumeCache;

    protected $fillable = [
        'user_id',
        'slug',
        'theme',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
