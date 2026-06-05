<?php

namespace App\Models\Concerns;

use App\Models\Course;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static void deleting(\Closure $callback)
 */
trait HasCourses
{
    protected static function bootHasCourses()
    {
        static::deleting(function ($model) {
            $model->courses->each->delete();
        });
    }

    public function courses(): MorphMany
    {
        return $this->morphMany(Course::class, 'courseable');
    }
}
