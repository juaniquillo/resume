<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\Cache\ResumeThemeCacheManager;
use App\Presenters\ResumePresenter;
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $isDraft = Helpers::isResumeInDraftState($user);

        if ($isDraft) {
            return response()->view('pages.resume-draft', [
                'user' => $user,
            ], 403);
        }

        /** @var GeneralOption|null $options */
        $options = $user->generalOptions;

        if ($request->user()?->id !== $user->id) {
            Helpers::incrementViews($options);
        }

        $theme = app(ResumeThemeCacheManager::class)->getThemePresenter($user);

        $presenter = new ResumePresenter($user, $theme);

        if (config('cache.resume.disable_cache')) {
            $presenter->clearCache();
        }

        /** @var Basic|null $basics */
        $basics = $user->resumeBasics();
        $description = $basics?->summary ? Str::limit(strip_tags($basics->summary), 160) : null;

        $image = route('resume.og.image', [
            'user' => $user,
            'v' => $options->og_image_version ?? 1,
        ]);

        return view('pages.resume', [
            'user' => $user,
            'theme' => $presenter->getTheme(),
            'resumeComponent' => $presenter->presentCached(),
            'description' => $description,
            'image' => $image,
            'noindex' => false,
        ]);
    }
}



