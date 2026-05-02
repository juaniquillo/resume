<?php

namespace App\Models\Concerns;

use App\Models\Highlight;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHighlights
{
    protected static function bootHasHighlights()
    {
        static::deleting(function ($model) {
            $model->highlights->each->delete();
        });
    }

    public function highlights(): MorphMany
    {
        return $this->morphMany(Highlight::class, 'highlightable');
    }
}
