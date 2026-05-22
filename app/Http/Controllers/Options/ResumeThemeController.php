<?php

namespace App\Http\Controllers\Options;

use App\Cruds\Squema\Options\ThemePickerCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResumeThemeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $model = $user?->theme()->first();

        $crud = ThemePickerCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $model
        );

        $crud->setFormAction(route('dashboard.resume.theme.update'));

        $form = $crud->form();

        return view('dashboard.options.theme')
            ->with('form', $form);
    }
}
