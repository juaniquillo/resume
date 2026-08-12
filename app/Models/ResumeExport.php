<?php

namespace App\Models;

use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Enums\ResumeTheme;
use App\Models\Concerns\InvalidatesResumeCache;
use App\Models\Concerns\Uuidable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read int $user_id
 * @property-read string|null $name
 * @property-read ResumeExportType $type
 * @property-read ResumeTheme|null $theme
 * @property-read bool $allow_download
 * @property-read string|null $file_path
 * @property-read ProcessStatus $status
 * @property-read string|null $error
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 */
#[Fillable([
    'user_id',
    'file_path',
    'status',
    'error',
    'type',
    'theme',
    'allow_download',
    'name',
])]
class ResumeExport extends Model
{
    use HasFactory, InvalidatesResumeCache, Uuidable;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (ResumeExport $export) {
            if ($export->file_path) {
                Storage::delete($export->file_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'type' => ResumeExportType::class,
            'theme' => ResumeTheme::class,
            'status' => ProcessStatus::class,
            'allow_download' => 'boolean',
        ];
    }
}
