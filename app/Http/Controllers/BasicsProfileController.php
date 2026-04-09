<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Profiles\ProfilesCrud;

class BasicsProfileController extends Controller
{
    public function index()
    {
        $form = ProfilesCrud::form();

        return view('dashboard.basics.profiles.index')
            ->with('form', $form);
    }
}
