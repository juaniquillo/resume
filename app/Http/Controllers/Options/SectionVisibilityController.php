<?php

namespace App\Http\Controllers\Options;

use App\Cruds\Squema\Options\SectionVisibilityCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionVisibilityController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $model = $user?->sectionVisibility()->first();

        $crud = SectionVisibilityCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $model
        );

        $crud->setFormAction(route('dashboard.resume.visibility.update'));

        $form = $crud->form();

        return view('dashboard.options.visibility')
            ->with('form', $form);
    }
}
