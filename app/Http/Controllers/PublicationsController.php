<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Publication\UpdatePublication;
use App\Cruds\Squema\Publications\PublicationsCrud;
use App\Http\Requests\PublicationsFormRequest;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PublicationsController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $crud = PublicationsCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.publications.store'));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $user->publications()->paginate(10);

        return view('dashboard.publications.index')
            ->with('form', $crud->form())
            ->with('table', $crud->makeTable($paginator))
            ->with('paginator', $paginator);
    }

    public function store(PublicationsFormRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->publications()->create($request->validated());

        return back()
            ->with('success', 'Publication created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, string $id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Publication $publication */
        $publication = $user->publications()->findOrFail($id);

        $crud = PublicationsCrud::build(
            values: $request->old() ?: $publication->toArray(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $publication,
        );

        $crud->setFormAction(route('dashboard.publications.update', $id));

        return view('dashboard.publications.edit')
            ->with('form', $crud->form());
    }

    public function update(PublicationsFormRequest $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Publication $publication */
        $publication = $user->publications()->findOrFail($id);

        (new UpdatePublication($request->validated(), $publication))->handle();

        return back()
            ->with('success', 'Publication updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Publication $publication */
        $publication = $user->publications()->findOrFail($id);

        $publication->delete();

        return back()
            ->with('success', 'Publication deleted successfully.');
    }
}
