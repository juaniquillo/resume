<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * @property-read string $user_id
 * @method static void created(\Closure $callback)
 * @method static void updated(\Closure $callback)
 * @method static void deleted(\Closure $callback)
 */
trait InvalidatesResumeCache
{
    protected static function bootInvalidatesResumeCache(): void
    {
        static::created(fn ($model) => static::invalidateResumeCache($model));
        static::updated(fn ($model) => static::invalidateResumeCache($model));
        static::deleted(fn ($model) => static::invalidateResumeCache($model));
    }

    protected static function invalidateResumeCache(mixed $model): void
    {
        $userId = $model->resolveResumeUserId();

        if ($userId) {
            $key = "resume:{$userId}:v";
            $current = (int) Cache::get($key, 0);
            Cache::forever($key, $current + 1);
        }
    }

    protected function resolveResumeUserId(): ?int
    {
        // Direct user_id
        if (isset($this->user_id)) {
            return (int) $this->user_id;
        }

        return null;
    }
}
