<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Presenters\Cache\ResumeThemeCacheManager;
use App\Presenters\ResumePresenter;
use App\Support\Helpers;

class ResumeController extends Controller
{
    public function __invoke(User $user)
    {
        if (Helpers::isResumeInDraftState($user)) {
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
