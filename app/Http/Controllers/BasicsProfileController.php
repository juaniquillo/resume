<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use App\Models\User;
use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BasicsProfileController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->resumeBasics();

        return view('dashboard.basics.profiles.index')
            ->with('basics', $basics)
            ->with('limit', ResumeLimit::PROFILES);
    }
}
