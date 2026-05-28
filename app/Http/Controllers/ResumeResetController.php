<?php

namespace App\Http\Controllers;

class ResumeResetController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.resume.reset');
    }
}
