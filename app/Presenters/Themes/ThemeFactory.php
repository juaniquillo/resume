<?php

namespace App\Presenters\Themes;

use App\Enums\ResumeTheme;
use App\Models\Theme;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;

class ThemeFactory
{
    public static function forUser(User $user): PresenterTheme
    {
        /** @var ?Theme $themeModel */
        $themeModel = $user->theme()->first();

        if (! $themeModel || ! $themeModel->theme) {
            return self::make(ResumeTheme::DEFAULT->value);
        }

        return self::make($themeModel->theme);
    }

    public static function make(string $theme): PresenterTheme
    {
        $theme = ResumeTheme::tryFrom($theme) ?? self::make(ResumeTheme::DEFAULT->value);

        return $theme->instance();
    }
}
