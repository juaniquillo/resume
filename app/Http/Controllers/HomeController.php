<?php

namespace App\Http\Controllers;

use App\Models\GeneralOption;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {

        $demo = null;
        $user = User::first();

        if ($user) {
            /** @var GeneralOption $options */
            $options = $user->generalOptions();
            $demo = $options->first()->slug;
        }

        return view('index')
            ->with('demo', $demo);
    }
}
