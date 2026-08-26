<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.interests.index')
            ->with('limit', ResumeLimit::INTERESTS);
    }
}
