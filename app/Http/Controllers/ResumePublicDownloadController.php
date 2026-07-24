<?php

namespace App\Http\Controllers;

use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Models\ResumeExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumePublicDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $uuid): StreamedResponse|RedirectResponse
    {
        $export = ResumeExport::where('uuid', $uuid)
            ->where('allow_download', true)
            ->where('status', ProcessStatus::COMPLETED)
            ->firstOrFail();

        if (! $export->file_path || ! Storage::exists($export->file_path)) {
            abort(404, 'The exported file was not found.');
        }

        /** @var ResumeExportType $enum */
        $enum = $export->type;

        $filename = str_replace(' ', '-', strtolower($export->user->name)).'-resume.'.$enum->extension();

        return Storage::download($export->file_path, $filename);
    }
}
