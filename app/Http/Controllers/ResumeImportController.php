<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Squema\ResumeImport\ResumeImportCrud;
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

        $imports = ResumeImport::where('user_id', $request->user()->id)
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

    public function store(ResumeImportFormRequest $request)
    {
        $file = $request->file(JsonFileFactory::NAME);
        $path = $file->store('imports/resumes');

        $import = ResumeImport::create([
            'user_id' => $request->user()->id,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        ProcessResumeImport::dispatch($import);

        return redirect()
            ->back()
            ->with('success', 'Resume import started successfully. It will be processed in the background.');
    }
}
