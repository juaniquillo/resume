<?php

namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read array|null $settings
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 */
class SectionVisibility extends Model
{
    use InvalidatesResumeCache;

    protected $fillable = [
        'user_id',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function resolveResumeUserId(): ?int
    {
        return (int) $this->user_id;
    }
}
