<?php

namespace App\Http\Controllers;

use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Http\Request;

class ResumePreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $theme = ThemeFactory::forUser($user);
        $presenter = new ResumePresenter($user, $theme);

        return view('pages.resume', [
            'user' => $user,
            'theme' => $presenter->getTheme(),
            'resumeComponent' => $presenter->present()->toHtml(),
            'minimalView' => true,
            'showThemeToggle' => true,
        ]);
    }
}
