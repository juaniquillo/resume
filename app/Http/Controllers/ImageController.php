<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    /**
     * Serve an image from storage using the basics uuid.
     */
    public function __invoke(string $uuid): StreamedResponse
    {
        $basics = Basic::where('uuid', $uuid)->firstOrFail();

        if (! $basics->image || ! Storage::exists($basics->image)) {
            abort(404);
        }

        return Storage::response($basics->image);
    }
}
