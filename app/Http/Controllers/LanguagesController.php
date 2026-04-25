<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Languages\LanguagesCrud;
use App\Http\Requests\LanguageFormRequest;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index(Request $request)
    {
        $languages = $request->user()
            ->languages()
            ->latest()
            ->paginate(10);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = LanguagesCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.languages.store'));

        $form = $crud->form();

        if (! $languages->isEmpty()) {
            $table = $crud->makeTable($languages);
        }

        return view('dashboard.languages.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $languages);
    }

    public function store(LanguageFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->languages()->create($validated);

        return redirect()
            ->back()->with('success', 'Language created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->languages()->findOrFail($id);

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = LanguagesCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.languages.update', $id));

        $form = $crud->form();

        return view('dashboard.languages.edit')
            ->with('form', $form);
    }

    public function update(LanguageFormRequest $request, int $id)
    {
        $model = $request->user()->languages()->findOrFail($id);

        $validated = $request->validated();

        $model->update($validated);

        return redirect()
            ->back()->with('success', 'Language updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->languages()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Language deleted successfully.');
    }
}
