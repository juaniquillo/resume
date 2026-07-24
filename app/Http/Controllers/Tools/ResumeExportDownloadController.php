<?php

namespace App\Http\Controllers\Tools;

use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Http\Controllers\Controller;
use App\Models\ResumeExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeExportDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $uuid): StreamedResponse|RedirectResponse
    {
        $user = $request->user();

        /** @var ResumeExport $export */
        $export = $user->resumeExports()
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($export->status !== ProcessStatus::COMPLETED || ! $export->file_path) {
            return redirect()->back()->with('error', 'The export is not ready for download.');
        }

        if (! Storage::exists($export->file_path)) {
            return redirect()->back()->with('error', 'The exported file was not found.');
        }

        /** @var ResumeExportType $enum */
        $enum = $export->type;

        $filename = str_replace(' ', '-', strtolower($user->name)).'-resume.'.$enum->extension();

        return Storage::download($export->file_path, $filename);
    }
}
