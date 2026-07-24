<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicsController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.basics.index');
    }
}



