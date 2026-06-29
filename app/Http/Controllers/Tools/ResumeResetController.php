<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ResumeResetController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.resume.reset');
    }
}
