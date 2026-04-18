<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Education\EducationCrud;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $crud = EducationCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        return view('dashboard.education.index')
            ->with('form', $crud->form());
    }

    public function store(Request $request)
    {
        // TODO: Implement saving logic
        return back();
    }
}
