<?php

namespace App\Models\Concerns;

use App\Models\Highlight;

trait HasHighlight
{
    public function highlights() {
        return $this->morphMany(Highlight::class, 'highlightable');
    }
}
