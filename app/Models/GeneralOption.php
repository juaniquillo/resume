<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $slug
 * @property-read string|null $theme
 * @property-read string $is_draft
 * @property-read string $hide_phone
 * @property-read string $hide_email
 * @property-read int $views
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 */
class GeneralOption extends Model
{
    use HasFactory;
    use InvalidatesResumeCache;

    protected $fillable = [
        'user_id',
        'slug',
        'theme',
        'is_draft',
        'hide_phone',
        'hide_email',
        'views',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
