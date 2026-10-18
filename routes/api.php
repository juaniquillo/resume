<?php

use App\Http\Controllers\Api\ResumeApiController;
use Illuminate\Support\Facades\Route;

/**
 * API: Public resume export (rate-limited, draft-protected).
 * - GET /api/resume/{user:slug} — whole resume as JSON
 * - GET /api/resume/{user:slug}?section=basics — specific section
 */
Route::middleware('throttle:5,1')
    ->get('resume/{user:slug}', ResumeApiController::class)
    ->name('api.resume');
