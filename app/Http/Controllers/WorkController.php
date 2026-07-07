<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
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

        $form = $crud->formWithTextareaSpanFull();

        if (! $works->isEmpty()) {
            $table = $crud->makeTable($works);
        }

        return view('dashboard.works.index')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $works);
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->works()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Work deleted successfully.');
    }
}
