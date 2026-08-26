<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferencesController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.references.index')
            ->with('limit', ResumeLimit::REFERENCES);
    }
}
