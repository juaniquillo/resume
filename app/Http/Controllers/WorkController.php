<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
use App\Http\Requests\WorkFormRequest;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $works = $request->user()
            ->works()
            ->latest()
            ->paginate(10);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = WorksCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.works.store'));

        $form = $crud->formWithTextareaSpanFull();

        if (! $works->isEmpty()) {
            $table = $crud->makeTable($works);
        }

        return view('dashboard.works.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $works);
    }

    public function store(WorkFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->works()->create($validated);

        return redirect()
            ->back()->with('success', 'Work created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->works()->findOrFail($id);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = WorksCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.works.update', $id));

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.works.edit')
            ->with('form', $form);
    }

    public function update(WorkFormRequest $request, int $id)
    {
        $model = $request->user()->works()->findOrFail($id);

        $validated = $request->validated();

        $updated = $model->update($validated);

        return redirect()
            ->back()->with('success', 'Work updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->works()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Work deleted successfully.');
    }
}
