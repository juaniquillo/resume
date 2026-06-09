<?php

namespace App\Models;

use App\Enums\ResumeTheme;
use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $slug
 * @property-read ResumeTheme|null $theme
 * @property-read bool $is_draft
 * @property-read bool $hide_phone
 * @property-read bool $hide_email
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

    protected function casts(): array
    {
        return [
            'theme' => ResumeTheme::class,
            'is_draft' => 'boolean',
            'hide_phone' => 'boolean',
            'hide_email' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
