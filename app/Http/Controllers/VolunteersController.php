<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;

class VolunteersController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.volunteers.index')
            ->with('limit', ResumeLimit::VOLUNTEERS);
    }
}
