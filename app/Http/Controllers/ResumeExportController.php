<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\ResumeExport\ResumeExportCrud;
use App\Jobs\ProcessResumeExport;
use App\Models\ResumeExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeExportController extends Controller
{
    public function index(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = ResumeExportCrud::build(
            values: $values,
            errors: $errors,
        );

        $crud->setFormAction(route('dashboard.resume.export.store'));

        $form = $crud->formWithButtonOnly();

        $exports = ResumeExport::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        $table = null;
        if (! $exports->isEmpty()) {
            $table = $crud->makeTable($exports);
        }

        return view('dashboard.resume.export')
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $exports);
    }

    public function store(Request $request)
    {
        if ($request->user()->resumeExports()->count() >= 5) {
            return redirect()
                ->back()
                ->with('error', 'You can only have up to 5 resume exports. Please delete an old one first.');
        }

        $export = ResumeExport::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        ProcessResumeExport::dispatch($export);

        return redirect()
            ->back()
            ->with('success', 'Resume export started successfully. It will be processed in the background.');
    }

    public function destroy(Request $request, int $id)
    {
        $export = ResumeExport::where('user_id', $request->user()->id)->findOrFail($id);

        if ($export->file_path) {
            Storage::delete($export->file_path);
        }

        $export->delete();

        return redirect()
            ->back()
            ->with('success', 'Resume export deleted successfully.');
    }
}
