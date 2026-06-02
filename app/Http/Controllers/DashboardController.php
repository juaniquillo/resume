<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();

        return view('dashboard.index', [
            'hasBasics' => $user->basics()->exists(),
            'views' => $user->generalOptions->views ?? 0,
        ]);
    }
}
