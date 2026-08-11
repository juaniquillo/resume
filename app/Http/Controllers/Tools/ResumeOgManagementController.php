<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResumeOgManagementController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.resume.og-image');
    }
}
