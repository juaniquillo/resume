<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $presenter = new ResumePresenter($user);

        if (config('cache.resume.disable_cache')) {
            $presenter->clearCache();
        }

        return view('pages.resume', [
            'user' => $user,
            'resumeComponent' => $presenter->presentCached(),
        ]);
    }
}
