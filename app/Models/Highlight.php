<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $highlightable_type
 * @property-read int $highlightable_id
 * @property-read string $highlight
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Model $highlightable
 */
class Highlight extends Model
{
    public function highlightable()
    {
        return $this->morphTo();
    }
}
