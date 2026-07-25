<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VolunteersController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.volunteers.index');
    }
}
