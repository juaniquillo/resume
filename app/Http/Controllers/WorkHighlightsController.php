<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Http\Requests\HighlightsFormRequest;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkHighlightsController extends Controller
{
    public function index(Request $request, int $id)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);

        $crud = HighlightsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(
            route('dashboard.works.highlights.store', $id)
        );

        $table = null;
        $highlights = $work->highlights()->paginate(10);

        if (! $highlights->isEmpty()) {
            $table = $crud->makeTable($highlights);
        }

        return view('dashboard.works.highlights.index')
            ->with('form', $crud->formWithTextareaSpanFull())
            ->with('table', $table)
            ->with('paginator', $highlights);
    }

    public function store(HighlightsFormRequest $request, int $id)
    {
        $validated = $request->validated();

        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);
        $work->highlights()->create($validated);

        return back()
            ->with('success', 'Highlight created successfully.');
    }

    public function edit(Request $request, int $id, int $highlightId)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);
        $highlight = $work->highlights()->findOrFail($highlightId);
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = HighlightsCrud::build(
            values: $values,
            errors: $errors,
            model: $highlight,
        );

        $crud->setFormAction(
            route('dashboard.works.highlights.update', [$id, $highlightId])
        );

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.works.highlights.edit')
            ->with('form', $form)
            ->with('highlight', $highlight);
    }

    public function update(HighlightsFormRequest $request, int $id, int $highlightId)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);
        $highlight = $work->highlights()->findOrFail($highlightId);
        $validated = $request->validated();

        $highlight->update($validated);

        return back()
            ->with('success', 'Highlight updated successfully.');
    }

    public function destroy(Request $request, int $id, int $highlightId)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);
        $highlight = $work->highlights()->findOrFail($highlightId);

        $highlight->delete();

        return back()
            ->with('success', 'Highlight deleted successfully.');
    }
}
