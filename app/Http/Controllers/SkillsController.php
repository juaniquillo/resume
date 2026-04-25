<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Skills\SkillsCrud;
use App\Http\Requests\SkillsFormRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class SkillsController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $crud = SkillsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.skills.store'));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $user->skills()->paginate(10);

        return view('dashboard.skills.index')
            ->with('form', $crud->form())
            ->with('table', $crud->makeTable($paginator))
            ->with('paginator', $paginator);
    }

    public function store(SkillsFormRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->skills()->create($request->validated());

        return back()
            ->with('success', 'Skill created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, string $id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Skill $skill */
        $skill = $user->skills()->findOrFail($id);

        $crud = SkillsCrud::build(
            values: $request->old() ?: $skill->toArray(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $skill,
        );

        $crud->setFormAction(route('dashboard.skills.update', $id));

        return view('dashboard.skills.edit')
            ->with('form', $crud->form());
    }

    public function update(SkillsFormRequest $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Skill $skill */
        $skill = $user->skills()->findOrFail($id);

        $skill->update($request->validated());

        return back()
            ->with('success', 'Skill updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Skill $skill */
        $skill = $user->skills()->findOrFail($id);

        $skill->delete();

        return back()
            ->with('success', 'Skill deleted successfully.');
    }
}
