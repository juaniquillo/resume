<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoverLettersController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.cover-letters.index');
    }
}
