<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Awards\AwardsCrud;
use Illuminate\Http\Request;

class AwardsController extends Controller
{
    public function __invoke(Request $request)
    {
        $crud = AwardsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        return view('dashboard.awards.index')
            ->with('form', $crud->formWithTextareaSpanFull());
    }
}
