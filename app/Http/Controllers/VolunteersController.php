<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Volunteer\UpdateVolunteer;
use App\Cruds\Squema\Volunteers\VolunteersCrud;
use App\Http\Requests\VolunteerFormRequest;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteersController extends Controller
{
    public function index(Request $request)
    {
        $volunteers = $request->user()
            ->volunteers()
            ->latest()
            ->paginate(10);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = VolunteersCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.volunteers.store'));

        $form = $crud->formWithTextareaSpanFull();

        if (! $volunteers->isEmpty()) {
            $table = $crud->makeTable($volunteers);
        }

        return view('dashboard.volunteers.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $volunteers);
    }

    public function store(VolunteerFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->volunteers()->create($validated);

        return redirect()
            ->back()->with('success', 'Volunteer entry created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->volunteers()->findOrFail($id);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = VolunteersCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.volunteers.update', $id));

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.volunteers.edit')
            ->with('form', $form);
    }

    public function update(VolunteerFormRequest $request, int $id)
    {
        /** @var Volunteer $model */
        $model = $request->user()->volunteers()->findOrFail($id);

        (new UpdateVolunteer($request->validated(), $model))->handle();

        return redirect()
            ->back()->with('success', 'Volunteer entry updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->volunteers()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Volunteer entry deleted successfully.');
    }
}
