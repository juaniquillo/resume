<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Volunteers\VolunteersCrud;
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
