<?php

namespace App\Presenters\Themes;

use App\Enums\ResumeTheme;
use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;

class ThemeFactory
{
    public static function forUser(User $user): PresenterTheme
    {
        /** @var ?GeneralOption $options */
        $options = $user->generalOptions()->first();

        if (! $options || ! $options->theme) {
            return self::make(ResumeTheme::DEFAULT->value);
        }

        return self::make($options->theme);
    }

    public static function make(string $theme): PresenterTheme
    {
        $theme = ResumeTheme::tryFrom($theme) ?? self::make(ResumeTheme::DEFAULT->value);

        return $theme->instance();
    }
}
