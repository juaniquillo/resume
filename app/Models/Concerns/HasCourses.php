<?php

namespace App\Models\Concerns;

use App\Models\Course;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCourses
{
    protected static function bootHasHighlights()
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
