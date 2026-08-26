<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.education.index')
            ->with('limit', ResumeLimit::EDUCATION);
    }
}
