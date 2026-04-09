<?php

namespace App\Http\Controllers\Auth;

use App\Cruds\Squema\Volunteers\VolunteersCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VolunteersController extends Controller
{
    public function __invoke(Request $request)
    {
        $crud = VolunteersCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        return view('dashboard.volunteers.index')
            ->with('form', $crud->formWithTextareaSpanFull());
    }
}
