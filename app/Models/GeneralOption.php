<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $user_id
 * @property-read string $slug
 * @property-read string $theme
 * @property-read bool $is_draft
 * @property-read bool $hide_phone
 * @property-read bool $hide_email
 */
class GeneralOption extends Model
{
    use InvalidatesResumeCache;

    protected $fillable = [
        'user_id',
        'slug',
        'theme',
        'is_draft',
        'hide_phone',
        'hide_email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
