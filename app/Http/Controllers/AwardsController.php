<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AwardsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.awards.index')
            ->with('limit', ResumeLimit::AWARDS);
    }
}
