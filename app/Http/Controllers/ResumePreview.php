<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Http\Request;

class ResumePreview extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $theme = ThemeFactory::forUser($user);

        $presenter = new ResumePresenter($user, $theme);
        // We clear cache for preview to ensure immediate feedback
        // $presenter->clearCache();

        return view('dashboard.resume.preview')
            ->with('name', $user->name)
            ->with('theme', $theme)
            ->with('resumeComponent', $presenter->presentCached());
    }
}
