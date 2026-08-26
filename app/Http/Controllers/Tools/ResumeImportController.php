<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResumeImportController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.resume.import')
            ->with('limit', ResumeLimit::IMPORTS);
    }
}
