<?php

namespace App\Http\Controllers;

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
