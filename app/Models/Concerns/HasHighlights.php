<?php

namespace App\Models\Concerns;

use App\Models\Highlight;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHighlights
{
    public function highlights(): MorphMany
    {
        return $this->morphMany(Highlight::class, 'highlightable');
    }
}
