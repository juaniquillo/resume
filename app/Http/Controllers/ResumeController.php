<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
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
