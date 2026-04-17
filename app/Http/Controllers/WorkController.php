<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $works = $request->user()
            ->works()
            ->latest()
            ->get();

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = WorksCrud::build(
            values: $values,
            errors: $errors,
        );

        if (! $works->isEmpty()) {
            $table = $crud->makeTable($works);
        }

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.works.index')
            ->with('form', $form)
            ->with('table', $table);
    }
}
