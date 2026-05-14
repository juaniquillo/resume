<?php

namespace App\Models;

use App\Models\Concerns\HasCourses;
use App\Models\Concerns\Uuidable;
use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $institution
 * @property-read string $url
 * @property-read string $area
 * @property-read string $study_type
 * @property-read string $score
 * @property-read string $user_id
 * @property-read Carbon $starts_at
 * @property-read Carbon|null $ends_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Collection<int, Course> $courses
 */
#[Guarded([])]
class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
    use HasCourses, HasFactory, Uuidable;

    protected $table = 'education';

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
