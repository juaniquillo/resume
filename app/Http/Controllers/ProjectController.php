<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Projects\ProjectsCrud;
use App\Http\Requests\ProjectsFormRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()
            ->projects()
            ->latest()
            ->paginate(10);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = ProjectsCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.projects.store'));

        $form = $crud->formWithTextareaSpanFull();

        if (! $projects->isEmpty()) {
            $table = $crud->makeTable($projects);
        }

        return view('dashboard.projects.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $projects);
    }

    public function store(ProjectsFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->projects()->create($validated);

        return redirect()
            ->back()->with('success', 'Project created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->projects()->findOrFail($id);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = ProjectsCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.projects.update', $id));

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.projects.edit')
            ->with('form', $form);
    }

    public function update(ProjectsFormRequest $request, int $id)
    {
        $model = $request->user()->projects()->findOrFail($id);

        $validated = $request->validated();

        $model->update($validated);

        return redirect()
            ->back()->with('success', 'Project updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->projects()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Project deleted successfully.');
    }
}
