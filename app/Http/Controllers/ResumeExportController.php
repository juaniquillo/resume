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
        $export = ResumeExport::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        ProcessResumeExport::dispatch($export);

        return redirect()
            ->back()
            ->with('success', 'Resume export started successfully. It will be processed in the background.');
    }

    public function download(Request $request, string $uuid)
    {
        $export = ResumeExport::where('user_id', $request->user()->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($export->status !== 'completed' || ! $export->file_path) {
            return redirect()->back()->with('error', 'The export is not ready for download.');
        }

        if (! Storage::exists($export->file_path)) {
            return redirect()->back()->with('error', 'The exported file was not found.');
        }

        return Storage::download($export->file_path);
    }
}
