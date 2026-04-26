<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Http\Requests\HighlightsFormRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectHighlightsController extends Controller
{
    public function index(Request $request, int $id)
    {
        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);

        $crud = HighlightsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            baseRoute: 'dashboard.projects.highlights',
        );

        $crud->setFormAction(
            route('dashboard.projects.highlights.store', $id)
        );

        $table = null;
        $highlights = $project->highlights()->paginate(10);

        if (! $highlights->isEmpty()) {
            $table = $crud->makeTable($highlights);
        }

        return view('dashboard.projects.highlights.index')
            ->with('form', $crud->formWithTextareaSpanFull())
            ->with('table', $table)
            ->with('paginator', $highlights);
    }

    public function store(HighlightsFormRequest $request, int $id)
    {
        $validated = $request->validated();

        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);
        $project->highlights()->create($validated);

        return back()
            ->with('success', 'Highlight created successfully.');
    }

    public function edit(Request $request, int $id, int $highlightId)
    {
        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);
        $highlight = $project->highlights()->findOrFail($highlightId);
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = HighlightsCrud::build(
            values: $values,
            errors: $errors,
            model: $highlight,
            baseRoute: 'dashboard.projects.highlights',
        );

        $crud->setFormAction(
            route('dashboard.projects.highlights.update', [$id, $highlightId])
        );

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.projects.highlights.edit')
            ->with('form', $form)
            ->with('highlight', $highlight);
    }

    public function update(HighlightsFormRequest $request, int $id, int $highlightId)
    {
        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);
        $highlight = $project->highlights()->findOrFail($highlightId);
        $validated = $request->validated();

        $highlight->update($validated);

        return back()
            ->with('success', 'Highlight updated successfully.');
    }

    public function destroy(Request $request, int $id, int $highlightId)
    {
        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);
        $highlight = $project->highlights()->findOrFail($highlightId);

        $highlight->delete();

        return back()
            ->with('success', 'Highlight deleted successfully.');
    }
}
