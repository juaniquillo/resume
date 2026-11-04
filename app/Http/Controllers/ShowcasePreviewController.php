<?php

namespace App\Http\Controllers;

use App\Enums\ResumeTheme;
use App\Presenters\ResumePresenter;
use App\Support\Showcase\ShowcaseFixture;
use Illuminate\Http\Request;

class ShowcasePreviewController extends Controller
{
    public function __invoke(Request $request, string $theme)
    {
        abort_unless(app()->environment('local', 'testing'), 404);

        $enum = ResumeTheme::tryFrom($theme);

        abort_if($enum === null || $enum->isHiddenFromShowcase(), 404);

        $user = ShowcaseFixture::user();

        abort_if($user === null || $user->resumeBasics() === null, 404, 'Showcase fixture not seeded. Run php artisan showcase:capture --fresh.');

        $presenterTheme = $enum->instance();
        $resumeComponent = new ResumePresenter($user, $presenterTheme);

        if (config('cache.resume.disable_cache')) {
            $resumeComponent->clearCache();
        }

        return view('pages.showcase-preview', [
            'user' => $user,
            'theme' => $presenterTheme,
            'themeLabel' => $enum->label(),
            'resumeComponent' => $resumeComponent->presentCached(),
            'dark' => $request->boolean('dark'),
            'noindex' => true,
        ]);
    }
}
