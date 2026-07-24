<?php

namespace App\Http\Controllers\Options;

use App\Http\Controllers\Controller;

class GeneralOptionsController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.options.general');
    }
}



