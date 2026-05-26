<?php

namespace App\Http\Controllers;

use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\Cache\ResumeThemeCacheManager;
use App\Presenters\ResumePresenter;

class ResumeController extends Controller
{
    public function __invoke(User $user)
    {
        /** @var GeneralOption|null $options */
        $options = $user->generalOptions;

        if ($options?->is_draft || ! $user->basics()->exists()) {
            return response()->view('pages.resume-draft', [
                'user' => $user,
            ], 403);
        }

        $theme = app(ResumeThemeCacheManager::class)->getThemePresenter($user);
        $presenter = new ResumePresenter($user, $theme);

        if (config('cache.resume.disable_cache')) {
            $presenter->clearCache();
        }

        return view('pages.resume', [
            'user' => $user,
            'theme' => $presenter->getTheme(),
            'resumeComponent' => $presenter->presentCached(),
        ]);
    }
}
