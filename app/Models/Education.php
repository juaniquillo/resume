<?php

namespace App\Models;

use App\Models\Concerns\HasCourses;
use App\Models\Concerns\Uuidable;
use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property-read string $starts_at
 * @property-read string $ends_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 */
#[Guarded([])]
class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
    use HasFactory, HasCourses, Uuidable;

    protected $table = 'education';
}
