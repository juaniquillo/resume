<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResumeImportController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.resume.import');
    }
}
