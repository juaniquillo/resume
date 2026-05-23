<?php

namespace App\Http\Controllers;

use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;

class ResumeController extends Controller
{
    public function __invoke(User $user)
    {
        /** @var GeneralOption|null $options */
        $options = $user->generalOptions;

        if ($options?->is_draft) {
            return response()->view('pages.resume-draft', [
                'user' => $user,
            ], 403);
        }

        $theme = ThemeFactory::forUser($user);
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
