<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResumeOgManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.resume.og-image');
    }
}
