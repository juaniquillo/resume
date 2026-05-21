<?php

namespace App\Models;

use App\Enums\ResumeExportType;
use App\Models\Concerns\Uuidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeExport extends Model
{
    use HasFactory, Uuidable;

    protected $fillable = [
        'user_id',
        'file_path',
        'status',
        'error',
        'type',
    ];

    protected $casts = [
        'type' => ResumeExportType::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
