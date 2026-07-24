<?php

namespace App\Http\Controllers\Options;

use App\Http\Controllers\Controller;

class SectionOrderingController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.options.ordering');
    }
}
