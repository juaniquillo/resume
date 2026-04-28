<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Locations\LocationsCrud;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BasicsLocationController extends Controller
{
    /**
     * @return View|string
     */
    public function __invoke(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        $form = null;
        $table = null;
        $location = null;

        if ($basics) {
            $location = $basics->location()->first();

            $crud = LocationsCrud::build(
                values: $values,
                errors: $errors,
                model: $location,
            );

            $crud->setFormAction(route('dashboard.basics.location.update'));

            $form = $crud->formWithInputsSpanFull();

        }

        return view('dashboard.basics.location.index')
            ->with('basics', $basics)
            ->with('form', $form)
            ->with('paginator', $location);
    }
}
