<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.projects.index')
            ->with('limit', ResumeLimit::PROJECTS);
    }
}
