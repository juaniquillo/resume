<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Award\UpdateAward;
use App\Cruds\Squema\Awards\AwardsCrud;
use App\Http\Requests\AwardsFormRequest;
use App\Models\Award;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class AwardsController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $crud = AwardsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.awards.store'));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $user?->awards()->paginate(10);

        return view('dashboard.awards.index')
            ->with('form', $crud->formWithTextareaSpanFull())
            ->with('table', $crud->makeTable($paginator))
            ->with('paginator', $paginator);
    }

    public function store(AwardsFormRequest $request): RedirectResponse
    {
        $request->user()?->awards()->create($request->validated());

        return back()
            ->with('success', 'Award created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, string $id)
    {
        $user = $request->user();

        /** @var Award $award */
        $award = $user?->awards()->findOrFail($id);

        $crud = AwardsCrud::build(
            values: $request->old() ?: $award->toArray(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $award,
        );

        $crud->setFormAction(route('dashboard.awards.update', $id));

        return view('dashboard.awards.edit')
            ->with('form', $crud->formWithTextareaSpanFull());
    }

    public function update(AwardsFormRequest $request, string $id): RedirectResponse
    {
        $user = $request->user();

        /** @var Award $award */
        $award = $user?->awards()->findOrFail($id);

        (new UpdateAward($request->validated(), $award))->handle();

        return back()
            ->with('success', 'Award updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $user = $request->user();

        $award = $user?->awards()->findOrFail($id);

        $award?->delete();

        return back()
            ->with('success', 'Award deleted successfully.');
    }
}
