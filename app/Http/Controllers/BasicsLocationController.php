<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Locations\LocationsCrud;
use Illuminate\Http\Request;

class BasicsLocationController extends Controller
{
    public function index()
    {
        $form = LocationsCrud::form();
        return view('dashboard.basics.locations.index')
            ->with('form', $form);
    }
}
