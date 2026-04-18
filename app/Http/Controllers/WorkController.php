<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
use App\Http\Requests\WorkFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $works = $request->user()
            ->works()
            ->latest()
            ->paginate();

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];
        $table = null;

        $crud = WorksCrud::build(
            values: $values,
            errors: $errors,
        );

        if (! $works->isEmpty()) {
            $table = $crud->makeTable($works);
        }

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.works.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('group', $works);
    }

    public function store(WorkFormRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->user()->id);

        $user->works()->create($validated);

        return redirect()
            ->back()->with('success', 'Work created successfully.');
    }
}
