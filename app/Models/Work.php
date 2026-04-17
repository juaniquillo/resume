<?php

namespace App\Models;

use App\Models\Concerns\HasHighlights;
use Database\Factories\WorkFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded([])]
class Work extends Model
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory,
        HasHighlights;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
