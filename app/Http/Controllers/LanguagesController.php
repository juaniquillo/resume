<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguagesController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.languages.index');
    }
}
