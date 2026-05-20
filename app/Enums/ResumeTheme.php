<?php

namespace App\Enums;

use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Themes\DefaultPresenterTheme;
use App\Presenters\Themes\TailwindPresenterTheme;

enum ResumeTheme: string
{
    case DEFAULT = 'default';
    case TAILWIND = 'tailwind';

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Retro-Modern (Space Mono)',
            self::TAILWIND => 'Modern Tailwind',
        };
    }

    public function instance(): PresenterTheme
    {
        return match ($this) {
            self::DEFAULT => new DefaultPresenterTheme,
            self::TAILWIND => new TailwindPresenterTheme,
        };
    }
}
