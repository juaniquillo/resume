<?php

namespace App\Http\Controllers;

use App\Models\ResumeImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeImportDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $id): StreamedResponse
    {
        /** @var ResumeImport $import */
        $import = ResumeImport::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return Storage::disk('local')->download(
            $import->file_path,
            $import->file_name
        );
    }
}
