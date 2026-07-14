<?php

namespace App\Models\Concerns;

use App\Models\Highlight;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static void deleting(\Closure $callback)
 */
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

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getUuid(): string
    {
        return $this->uuid;
    }

   public  function getUserId(): int
    {
        return $this->user_id;
    }

}
