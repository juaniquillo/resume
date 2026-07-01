<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BasicsLocationController extends Controller
{
    /**
     * @return View|string
     */
    public function __invoke(Request $request)
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->resumeBasics();

        return view('dashboard.basics.location.index')
            ->with('basics', $basics);
    }
}
