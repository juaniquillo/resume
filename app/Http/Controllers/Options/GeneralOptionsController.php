<?php

namespace App\Http\Controllers\Options;

use App\Cruds\Squema\Options\GeneralOptionsCrud;
use App\Http\Controllers\Controller;
use App\Models\GeneralOption;
use Illuminate\Http\Request;

class GeneralOptionsController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        /** @var GeneralOption|null $options */
        $options = $user->generalOptions;

        $values = $request->old();
        if (empty($values) && $options) {
            $values = [
                'slug' => $options->slug,
                'theme' => $options->theme,
            ];
        }

        $crud = GeneralOptionsCrud::build(
            values: $values,
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $user
        );

        $crud->setFormAction(route('dashboard.resume.general.update'));

        $form = $crud->form();

        return view('dashboard.options.general')
            ->with('form', $form);
    }
}
