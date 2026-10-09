<?php

namespace App\Enums;

use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Themes\BlankThemePresenter;
use App\Presenters\Themes\BoldPresenterTheme;
use App\Presenters\Themes\DefaultPresenterTheme;
use App\Presenters\Themes\ElegantPresenterTheme;
use App\Presenters\Themes\GithubPresenterTheme;
use App\Presenters\Themes\PdfPresenterTheme;
use App\Presenters\Themes\ProfessionalPresenterTheme;
use App\Presenters\Themes\TerminalPresenterTheme;

enum ResumeTheme: string
{
    case DEFAULT = 'default';
    case ELEGANT = 'elegant';
    case BLANK = 'blank';
    case BOLD = 'bold';
    case PDF = 'pdf';
    case PROFESSIONAL = 'professional';
    case TERMINAL = 'terminal';
    case GITHUB = 'github';

    public function isHiddenFromShowcase(): bool
    {
        return match ($this) {
            self::BLANK, self::PDF => true,
            default => false,
        };
    }

    /**
     * Themes featured in the landing page showcase.
     *
     * New themes appear here automatically; hide them with
     * isHiddenFromShowcase() instead. Remember to capture their
     * screenshots, otherwise their cards render broken images.
     *
     * @return list<self>
     */
    public static function showcase(): array
    {
        return array_values(
            array_filter(self::cases(), fn (self $theme) => ! $theme->isHiddenFromShowcase())
        );
    }

    /**
     * Ready-to-render data for the landing page theme showcase.
     *
     * @return list<array{key: string, label: string, tab: string}>
     */
    public static function showcaseData(): array
    {
        return array_map(
            fn (self $theme) => [
                'key' => $theme->value,
                'label' => $theme->label(),
                'tab' => $theme->shortLabel(),
            ],
            self::showcase(),
        );
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::ELEGANT => 'Elegant',
            self::BLANK => 'Blank',
            self::BOLD => 'Bold',
            self::PDF => 'PDF',
            self::PROFESSIONAL => 'Professional',
            self::TERMINAL => 'Terminal',
            self::GITHUB => 'GitHub',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Retro-Modern (Space Mono)',
            self::ELEGANT => 'Elegant Serif',
            self::BLANK => 'Blank Theme',
            self::BOLD => 'Modern & Bold',
            self::PDF => 'PDF Optimized',
            self::TERMINAL => 'Terminal Console',
            self::PROFESSIONAL => 'Professional Layout',
            self::GITHUB => 'GitHub Markdown',
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
            self::TERMINAL => new TerminalPresenterTheme,
            self::PROFESSIONAL => new ProfessionalPresenterTheme,
            self::GITHUB => new GithubPresenterTheme,
        };
    }
}
