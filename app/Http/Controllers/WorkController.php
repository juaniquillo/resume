<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.works.index');
    }
}



