<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Squema\ResumeImport\ResumeImportCrud;
use App\Http\Requests\ResumeImportFormRequest;
use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use JustSteveKing\Resume\Factories\ResumeFactory;

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
        if ($request->user()->resumeImports()->count() >= 5) {
            return redirect()
                ->back()
                ->with('error', 'You can only have up to 5 resume imports. Please delete an old one first.');
        }

        /** @var UploadedFile $file */
        $file = $request->file(JsonFileFactory::NAME);

        try {
            $json = file_get_contents($file->getRealPath());
            ResumeFactory::fromJson($json)->validate();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                JsonFileFactory::NAME => ['The provided file does not conform to the JSON Resume schema.'],
            ]);
        }

        $path = $file->store('imports/resumes');

        $import = ResumeImport::create([
            'user_id' => $request->user()->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        ProcessResumeImport::dispatch($import);

        return redirect()
            ->back()
            ->with('success', 'Resume import started successfully. It will be processed in the background.');
    }

    public function destroy(Request $request, int $id)
    {
        $import = ResumeImport::where('user_id', $request->user()->id)->findOrFail($id);

        if ($import->file_path) {
            Storage::delete($import->file_path);
        }

        $import->delete();

        return redirect()
            ->back()
            ->with('success', 'Resume import deleted successfully.');
    }
}
