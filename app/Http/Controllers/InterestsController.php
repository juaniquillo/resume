<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Interest\UpdateInterest;
use App\Cruds\Squema\Interests\InterestsCrud;
use App\Http\Requests\InterestFormRequest;
use App\Models\Interest;
use Illuminate\Http\Request;

class InterestsController extends Controller
{
    public function index(Request $request)
    {
        $interests = $request->user()
            ->interests()
            ->latest()
            ->paginate(10);

        $values = $request->old();

        // Convert keywords array to comma-separated string for the form
        if (isset($values['keywords']) && is_array($values['keywords'])) {
            $values['keywords'] = implode(', ', $values['keywords']);
        }

        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = InterestsCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.interests.store'));

        $form = $crud->form();

        if (! $interests->isEmpty()) {
            $table = $crud->makeTable($interests);
        }

        return view('dashboard.interests.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $interests);
    }

    public function store(InterestFormRequest $request)
    {
        $validated = $request->validated();

        $request->user()->interests()->create($validated);

        return redirect()
            ->back()->with('success', 'Interest created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $model = $request->user()->interests()->findOrFail($id);

        $values = $request->old();

        if (empty($values) && ! empty($model->keywords)) {
            $values['keywords'] = implode(', ', $model->keywords);
        } elseif (isset($values['keywords']) && is_array($values['keywords'])) {
            $values['keywords'] = implode(', ', $values['keywords']);
        }

        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = InterestsCrud::build(
            values: $values,
            errors: $errors,
            model: $model,
        );

        $crud->setFormAction(route('dashboard.interests.update', $id));

        $form = $crud->form();

        return view('dashboard.interests.edit')
            ->with('form', $form);
    }

    public function update(InterestFormRequest $request, int $id)
    {
        /** @var Interest $model */
        $model = $request->user()->interests()->findOrFail($id);

        (new UpdateInterest($request->validated(), $model))->handle();

        return redirect()
            ->back()->with('success', 'Interest updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->interests()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Interest deleted successfully.');
    }
}
