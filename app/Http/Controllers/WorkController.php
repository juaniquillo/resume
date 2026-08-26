<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.works.index')
            ->with('limit', ResumeLimit::WORK);
    }
}
