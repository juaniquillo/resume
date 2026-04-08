<?php

namespace App\Http\Controllers\Auth;

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BasicsController extends Controller
{
    public function __invoke(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        // $model = Basic::query()->find(1);

        $form = BasicsCrud::formWithTextareaSpanFull(
            values: $values,
            errors: $errors,
            model: $model ?? null
        );

        return view('dashboard.basics.index')
            ->with('form', $form);
    }
}
