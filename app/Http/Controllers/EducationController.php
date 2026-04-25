<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Education\EducationCrud;
use App\Http\Requests\EducationFormRequest;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class EducationController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $crud = EducationCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.education.store'));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $user?->education()->paginate(10);

        return view('dashboard.education.index')
            ->with('form', $crud->formWithInputsSpanFull())
            ->with('table', $crud->makeTable($paginator))
            ->with('paginator', $paginator);
    }

    public function store(EducationFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()?->education()->create($validated);

        return back()
            ->with('success', 'Education created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, string $id)
    {
        $user = $request->user();

        /** @var Education $education */
        $education = $user?->education()->findOrFail($id);

        $crud = EducationCrud::build(
            values: $request->old() ?: $education->toArray(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $education,
        );

        $crud->setFormAction(route('dashboard.education.update', $id));

        return view('dashboard.education.edit')
            ->with('form', $crud->formWithInputsSpanFull());
    }

    public function update(EducationFormRequest $request, string $id): RedirectResponse
    {
        $user = $request->user();

        /** @var Education $education */
        $education = $user?->education()->findOrFail($id);

        $education->update($request->validated());

        return back()
            ->with('success', 'Education updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $user = $request->user();

        $education = $user->education()->findOrFail($id);

        $education->delete();

        return back()
            ->with('success', 'Education deleted successfully.');
    }
}
