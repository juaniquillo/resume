<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Volunteer\CreateHighlight;
use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Http\Requests\HighlightsFormRequest;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteersHighlightsController extends Controller
{
    public function index(Request $request, int $id)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);

        $crud = HighlightsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            baseRoute: 'dashboard.volunteers.highlights',
        );

        $crud->setFormAction(
            route('dashboard.volunteers.highlights.store', $id)
        );

        $table = null;
        $highlights = $volunteer->highlights()->paginate(10);

        if (! $highlights->isEmpty()) {
            $table = $crud->makeTable($highlights);
        }

        return view('dashboard.volunteers.highlights.index')
            ->with('form', $crud->formWithTextareaSpanFull())
            ->with('table', $table)
            ->with('paginator', $highlights);
    }

    public function store(HighlightsFormRequest $request, int $id)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);

        (new CreateHighlight($request->validated(), $volunteer))->handle();

        return back()
            ->with('success', 'Highlight created successfully.');
    }

    public function edit(Request $request, int $id, int $highlightId)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);
        $highlight = $volunteer->highlights()->findOrFail($highlightId);
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = HighlightsCrud::build(
            values: $values,
            errors: $errors,
            model: $highlight,
            baseRoute: 'dashboard.volunteers.highlights',
        );

        $crud->setFormAction(
            route('dashboard.volunteers.highlights.update', [$id, $highlightId])
        );

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.volunteers.highlights.edit')
            ->with('form', $form)
            ->with('highlight', $highlight);
    }

    public function update(HighlightsFormRequest $request, int $id, int $highlightId)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);
        $highlight = $volunteer->highlights()->findOrFail($highlightId);
        $validated = $request->validated();

        $highlight->update($validated);

        return back()
            ->with('success', 'Highlight updated successfully.');
    }

    public function destroy(Request $request, int $id, int $highlightId)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);
        $highlight = $volunteer->highlights()->findOrFail($highlightId);

        $highlight->delete();

        return back()
            ->with('success', 'Highlight deleted successfully.');
    }
}
