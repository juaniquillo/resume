<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.publications.index')
            ->with('limit', ResumeLimit::PUBLICATIONS);
    }
}
