<?php

namespace App\Enums;

use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Themes\BlankThemePresenter;
use App\Presenters\Themes\BoldPresenterTheme;
use App\Presenters\Themes\DefaultPresenterTheme;
use App\Presenters\Themes\ElegantPresenterTheme;
use App\Presenters\Themes\PdfPresenterTheme;

enum ResumeTheme: string
{
    case DEFAULT = 'default';
    case ELEGANT = 'elegant';
    case BLANK = 'blank';
    case BOLD = 'bold';
    case PDF = 'pdf';

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Retro-Modern (Space Mono)',
            self::ELEGANT => 'Elegant Serif',
            self::BLANK => 'Blank Theme',
            self::BOLD => 'Modern & Bold',
            self::PDF => 'PDF Optimized',
        };
    }

    public function instance(): PresenterTheme
    {
        return match ($this) {
            self::DEFAULT => new DefaultPresenterTheme,
            self::ELEGANT => new ElegantPresenterTheme,
            self::BLANK => new BlankThemePresenter,
            self::BOLD => new BoldPresenterTheme,
            self::PDF => new PdfPresenterTheme,
        };
    }
}
