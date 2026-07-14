<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Common\UpdateHighlight;
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

        return view('dashboard.works.highlights.index')
            ->with('model', $work);
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
            baseRoute: 'dashboard.works.highlights',
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

        (new UpdateHighlight($request->validated(), $highlight))->handle();

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
