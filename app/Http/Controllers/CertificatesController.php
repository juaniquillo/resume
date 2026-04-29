<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Certificate\UpdateCertificate;
use App\Cruds\Squema\Certificates\CertificatesCrud;
use App\Http\Requests\CertificatesFormRequest;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class CertificatesController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $crud = CertificatesCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
        );

        $crud->setFormAction(route('dashboard.certificates.store'));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $user->certificates()->paginate(10);

        return view('dashboard.certificates.index')
            ->with('form', $crud->formWithInputsSpanFull())
            ->with('table', $crud->makeTable($paginator))
            ->with('paginator', $paginator);
    }

    public function store(CertificatesFormRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->certificates()->create($request->validated());

        return back()
            ->with('success', 'Certificate created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, string $id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Certificate $certificate */
        $certificate = $user->certificates()->findOrFail($id);

        $crud = CertificatesCrud::build(
            values: $request->old() ?: $certificate->toArray(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            model: $certificate,
        );

        $crud->setFormAction(route('dashboard.certificates.update', $id));

        return view('dashboard.certificates.edit')
            ->with('form', $crud->formWithInputsSpanFull());
    }

    public function update(CertificatesFormRequest $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Certificate $certificate */
        $certificate = $user->certificates()->findOrFail($id);

        (new UpdateCertificate($request->validated(), $certificate))->handle();

        return back()
            ->with('success', 'Certificate updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Certificate $certificate */
        $certificate = $user->certificates()->findOrFail($id);

        $certificate->delete();

        return back()
            ->with('success', 'Certificate deleted successfully.');
    }
}
