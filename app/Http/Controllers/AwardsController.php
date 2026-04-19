<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Awards\AwardsCrud;
use Illuminate\Http\Request;

class AwardsController extends Controller
{
    public function index(Request $request)
    {
        $crud = AwardsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.awards.store'));

        return view('dashboard.awards.index')
            ->with('form', $crud->formWithTextareaSpanFull());
    }

    public function store(Request $request)
    {
        // TODO: Implement saving logic
        return back();
    }
}
