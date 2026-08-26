<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguagesController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.languages.index')
            ->with('limit', ResumeLimit::LANGUAGES);
    }
}
