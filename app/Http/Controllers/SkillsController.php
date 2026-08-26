<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.skills.index')
            ->with('limit', ResumeLimit::SKILLS);
    }
}
