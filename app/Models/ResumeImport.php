<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $file_path
 * @property string $status
 * @property string|null $error
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class ResumeImport extends Model
{
    protected $fillable = [
        'user_id',
        'file_path',
        'status',
        'error',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
