<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Export\CreateResumeExport;
use App\Cruds\Squema\ResumeExport\ResumeExportCrud;
use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Http\Requests\Resume\ResumeExportFormRequest;
use App\Jobs\ProcessJsonExport;
use App\Jobs\ProcessPdfExport;
use App\Models\ResumeExport;
use Illuminate\Http\Request;

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

        $form = $crud->form();

        $user = $request->user();

        $exports = $user
            ->resumeExports()
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

    public function store(ResumeExportFormRequest $request, CreateResumeExport $action)
    {
        if ($request->user()->resumeExports()->count() >= 5) {
            return redirect()
                ->back()
                ->with('error', 'You can only have up to 5 resume exports. Please delete an old one first.');
        }

        $export = $action->handle($request->user(), [
            'type' => $request->validated('type'),
            'theme' => $request->validated('theme'),
            'allow_download' => $request->boolean('allow_download'),
        ]);

        match ($export->type) {
            ResumeExportType::JSON => ProcessJsonExport::dispatch($export),
            ResumeExportType::PDF => ProcessPdfExport::dispatch($export),
        };

        return redirect()
            ->back()
            ->with('success', 'Resume export started successfully. It will be processed in the background.');
    }

    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        /** @var ResumeExport $export */
        $export = $user->resumeExports()->findOrFail($id);

        if (! in_array($export->status, [ProcessStatus::COMPLETED, ProcessStatus::FAILED])) {
            return redirect()
                ->back()
                ->with('error', 'Only completed or failed exports can be deleted.');
        }

        $export->delete();

        return redirect()
            ->back()
            ->with('success', 'Resume export deleted successfully.');
    }
}
