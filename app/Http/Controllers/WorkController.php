<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $form = WorksCrud::build(
            values: $values,
            errors: $errors,
        )->formWithTextareaSpanFull();

        return view('dashboard.works.index')
            ->with('form', $form);
    }
}
