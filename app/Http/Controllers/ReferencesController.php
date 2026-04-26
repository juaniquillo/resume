<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\References\ReferencesCrud;
use App\Http\Requests\ReferenceFormRequest;
use Illuminate\Http\Request;

class ReferencesController extends Controller
{
    public function index(Request $request)
    {
        $references = $request->user()
            ->references()
            ->latest()
            ->paginate(10);

        $values = $request->old();

        // Convert keywords array to comma-separated string for the form
        if (isset($values['keywords']) && is_array($values['keywords'])) {
            $values['keywords'] = implode(', ', $values['keywords']);
        }

        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = ReferencesCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.references.store'));

        $form = $crud->form();

        if (! $references->isEmpty()) {
            $table = $crud->makeTable($references);
        }

        return view('dashboard.references.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $references);
    }

    public function store(ReferenceFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->references()->create($validated);

        return redirect()
            ->back()->with('success', 'Reference created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->references()->findOrFail($id);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = ReferencesCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.references.update', $id));

        $form = $crud->form();

        return view('dashboard.references.edit')
            ->with('form', $form);
    }

    public function update(ReferenceFormRequest $request, int $id)
    {
        $model = $request->user()->references()->findOrFail($id);

        $validated = $request->validated();

        $model->update($validated);

        return redirect()
            ->back()->with('success', 'Reference updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->references()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Reference deleted successfully.');
    }
}
