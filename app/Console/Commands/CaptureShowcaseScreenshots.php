<?php

namespace App\Console\Commands;

use App\Enums\ResumeTheme;
use App\Support\Showcase\ShowcaseFixture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\LaravelScreenshot\Facades\Screenshot;
use Throwable;

#[Signature('showcase:capture {theme? : Resume theme key (default: all showcased themes)} {--fresh : Reseed the showcase fixture before capturing}')]
#[Description('Capture landing showcase screenshots (light + dark) for resume themes')]
class CaptureShowcaseScreenshots extends Command
{
    public function handle(): int
    {
        $key = $this->argument('theme');

        if ($key !== null) {
            $theme = ResumeTheme::tryFrom($key);

            if ($theme === null) {
                $this->error("Unknown theme [{$key}].");

                return self::FAILURE;
            }

            $themes = [$theme];
        } else {
            $themes = ResumeTheme::showcase();
        }

        $this->info('Seeding showcase fixture...');
        ShowcaseFixture::seed($this->option('fresh'));

        foreach ($themes as $theme) {
            $this->capture($theme);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }

    private function capture(ResumeTheme $theme): void
    {
        $light = public_path("images/themes/{$theme->value}.png");
        $dark = public_path("images/themes/{$theme->value}-dark.png");

        try {
            Screenshot::url(route('showcase.preview', $theme->value))
                ->size(1200, 1600)
                ->deviceScaleFactor(1)
                ->waitUntil('networkidle0')
                ->save($light);

            Screenshot::url(route('showcase.preview', ['theme' => $theme->value, 'dark' => 1]))
                ->size(1200, 1600)
                ->deviceScaleFactor(1)
                ->waitUntil('networkidle0')
                ->save($dark);
        } catch (Throwable $e) {
            $this->error("Could not capture [{$theme->value}]: {$e->getMessage()} (Browsershot needs Chrome + network access to the served app.)");

            return;
        }

        $this->info("Captured {$theme->value}.png + {$theme->value}-dark.png");
    }
}
