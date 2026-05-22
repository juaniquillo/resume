<?php

namespace App\Http\Controllers\Options;

use App\Cruds\Squema\Options\UserSlugCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResumeSlugController extends Controller
{
    public function __invoke(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $user = $request->user();

        $crud = UserSlugCrud::build(
            values: $values,
            errors: $errors,
            model: $user
        );

        $form = $crud->form();

        return view('dashboard.options.slug')
            ->with('form', $form);
    }
}
