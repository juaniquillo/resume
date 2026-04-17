<?php

namespace App\Models\Concerns;

use App\Models\Highlight;

trait HasHighlights
{
    public function highlights()
    {
        return $this->morphMany(Highlight::class, 'highlightable');
    }
}
