<?php

namespace App\Http\Controllers\Tools;

use App\Actions\Resume\Import\CreateResumeImport;
use App\Cruds\Schema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeImportFormRequest;
use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use Illuminate\Http\Request;

class ResumeImportController extends Controller
{
    public function index(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = ResumeImportCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.resume.import.store'));

        $form = $crud->formWithUploadSpanFull();

        $user = $request->user();

        $imports = $user
            ->resumeImports()
            ->latest()
            ->paginate(10);

        $table = null;
        if (! $imports->isEmpty()) {
            $table = $crud->makeTable($imports);
        }

        return view('dashboard.resume.import')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $imports);
    }

    public function store(ResumeImportFormRequest $request, CreateResumeImport $action)
    {
        $user = $request->user();

        if ($user->resumeImports()->count() >= 5) {
            return redirect()
                ->back()
                ->with('error', 'You can only have up to 5 resume imports. Please delete an old one first.');
        }

        $import = $action->handle($user, [
            JsonFileFactory::NAME => $request->file(JsonFileFactory::NAME),
        ]);

        ProcessResumeImport::dispatch($import);

        return redirect()
            ->back()
            ->with('success', 'Resume import started successfully. It will be processed in the background.');
    }

    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        /** @var ResumeImport $import */
        $import = $user->resumeImports()->findOrFail($id);

        if (! ResumeImportCrud::canShowDeleteButton($import->status)) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete a resume import that is pending or processing.');
        }

        $import->delete();

        return redirect()
            ->back()
            ->with('success', 'Resume import deleted successfully.');
    }
}




