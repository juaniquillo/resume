<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $courseable_type
 * @property-read int $courseable_id
 * @property-read string $course
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Model $courseable
 */
#[Guarded([])]
class Course extends Model
{
    public function courseable()
    {
        return $this->morphTo();
    }
}
