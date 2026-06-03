<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return view('dashboard.index', [
            'hasBasics' => (bool) $user->resumeBasics(),
            'views' => $user->generalOptions->views ?? 0,
        ]);
    }
}
