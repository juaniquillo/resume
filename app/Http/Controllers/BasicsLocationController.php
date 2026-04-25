<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Locations\LocationsCrud;
use App\Http\Requests\LocationsFormRequest;
use App\Models\Basic;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BasicsLocationController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        $form = null;
        $table = null;
        $locations = null;

        if ($basics) {
            $locations = $basics->locations()->paginate(10);

            $crud = LocationsCrud::build(
                values: $values,
                errors: $errors,
            );

            $crud->setFormAction(route('dashboard.basics.locations.store'));

            $form = $crud->formWithInputsSpanFull();
            $table = $crud->makeTable($locations);

        }

        return view('dashboard.basics.locations.index')
            ->with('basics', $basics)
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $locations);
    }

    public function store(LocationsFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        if (! $basics) {
            return back()
                ->with('custom_error', __('basics.errors.basics_not_found'));
        }

        $basics->locations()->create($validated);

        return back()
            ->with('success', 'Location created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, int $id)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();
        $form = null;

        if ($basics) {
            $location = $basics->locations()->findOrFail($id);
            $crud = LocationsCrud::build(
                values: $values ?: $location->toArray(),
                errors: $errors,
                model: $location,
            );

            $crud->setFormAction(route('dashboard.basics.locations.update', $id));
            $form = $crud->formWithInputsSpanFull();
        }

        return view('dashboard.basics.locations.edit')
            ->with('form', $form);

    }

    public function update(LocationsFormRequest $request, int $id): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic $basics */
        $basics = $user->basics()->firstOrFail();

        /** @var Location $location */
        $location = $basics->locations()->findOrFail($id);

        $location->update($validated);

        return back()
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic $basics */
        $basics = $user->basics()->firstOrFail();

        /** @var Location $location */
        $location = $basics->locations()->findOrFail($id);

        $location->delete();

        return back()
            ->with('success', 'Location deleted successfully.');
    }
}
