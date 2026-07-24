<?php

namespace App\Models;

use App\Enums\ProcessStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $file_path
 * @property-read string $file_name
 * @property-read ProcessStatus $status
 * @property-read string|null $error
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 */
class ResumeImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_name',
        'status',
        'error',
    ];

    protected $casts = [
        'status' => ProcessStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (ResumeImport $import) {
            if ($import->file_path) {
                Storage::delete($import->file_path);
            }
        });
    }
}



