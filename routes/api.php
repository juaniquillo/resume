<?php

use App\Http\Controllers\Api\ResumeApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

/**
 * API: Public resume export (rate-limited, draft-protected).
 * - GET /api/resume/{user:slug} — whole resume as JSON
 * - GET /api/resume/{user:slug}?section=basics — specific section
 */
Route::middleware('throttle:5,1')
    ->get('resume/{user:slug}', [ResumeApiController::class, '__invoke'])
    ->name('api.resume');
