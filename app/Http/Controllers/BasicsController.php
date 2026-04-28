<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\BasicsCrud;
use Illuminate\Http\Request;

class BasicsController extends Controller
{
    public function __invoke(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $user = $request->user();

        $model = $user?->basics()->first();

        $crud = BasicsCrud::build(
            values: $values,
            errors: $errors,
            model: $model
        );

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.basics.index')
            ->with('firstTime', ! $model)
            ->with('form', $form);
    }
}
