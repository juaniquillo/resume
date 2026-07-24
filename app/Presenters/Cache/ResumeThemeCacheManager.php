<?php

namespace App\Presenters\Cache;

use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Themes\ThemeFactory;

/**
 * Laravel Singleton
 */
class ResumeThemeCacheManager
{
    /** @var array<int, PresenterTheme> */
    private $themes = [];

    public function getThemePresenter(User $user): PresenterTheme
    {
        if ($this->themes[$user->id] ?? null) {
            return $this->themes[$user->id];
        }

        $theme = ThemeFactory::forUser($user);

        return $this->themes[$user->id] ??= $theme;
    }
}



