<?php

namespace App\Presenters\Cache;

use App\Models\User;
use App\Presenters\Contracts\CacheManager;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\ResumePresenter;
use Illuminate\Support\Facades\Cache;

class ResumePresenterCacheManager implements CacheManager
{
    public function __construct(
        private User $user,
        private ?PresenterTheme $theme = null,
    ) {}

    public function present(ResumePresenter $presenter): string
    {
        $key = $this->getCacheKey();
        $cached = Cache::get($key);

        if ($cached) {
            return $cached;
        }

        return $this->cachePresenter($key, $presenter->present()->toHtml());

    }

    public function cachePresenter(string $key, string $html): string
    {
        $inAWeek = now()->addWeek();

        return Cache::remember($key, $inAWeek, fn () => $html);
    }

    public function getCacheKey(): string
    {
        $theme = $this->theme ?? app(ResumeThemeCacheManager::class)->getThemePresenter($this->user);
        $themeHash = md5(get_class($theme));

        return "resume:{$this->user->id}:{$themeHash}";
    }

    public function clearCache(): void
    {
        $key = $this->getCacheKey();
        Cache::forget($key);
    }
}
