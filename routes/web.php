<?php

use App\Http\Controllers\AwardsController;
use App\Http\Controllers\BasicsController;
use App\Http\Controllers\BasicsCreateController;
use App\Http\Controllers\BasicsLocationController;
use App\Http\Controllers\BasicsProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\VolunteersController;
use App\Http\Controllers\VolunteersHighlightsController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkHighlightsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/', DashboardController::class)->name('dashboard');

        /**
         * Basics
         */
        Route::get('basics', BasicsController::class)->name('dashboard.basics');
        Route::post('basics', BasicsCreateController::class)->name('dashboard.basics.update');

        Route::get('basics/locations', [BasicsLocationController::class, 'index'])->name('dashboard.basics.locations');
        Route::get('basics/profiles', [BasicsProfileController::class, 'index'])->name('dashboard.basics.profiles');

        /**
         * Works
         */
        Route::get('works', [WorkController::class, 'index'])->name('dashboard.works');
        Route::post('works', [WorkController::class, 'store'])->name('dashboard.works.store');
        Route::get('works/{id}/edit', [WorkController::class, 'edit'])->name('dashboard.works.edit');
        Route::post('works/{id}', [WorkController::class, 'update'])->name('dashboard.works.update');
        Route::delete('works/{id}', [WorkController::class, 'destroy'])->name('dashboard.works.destroy');

        Route::get('works/{id}/highlights', [WorkHighlightsController::class, 'index'])->name('dashboard.works.highlights');
        Route::post('works/{id}/highlights', [WorkHighlightsController::class, 'store'])->name('dashboard.works.highlights.store');
        Route::get('works/{id}/highlights/{highlightId}/edit', [WorkHighlightsController::class, 'edit'])->name('dashboard.works.highlights.edit');
        Route::post('works/{id}/highlights/{highlightId}', [WorkHighlightsController::class, 'update'])->name('dashboard.works.highlights.update');
        Route::delete('works/{id}/highlights/{highlightId}', [WorkHighlightsController::class, 'destroy'])->name('dashboard.works.highlights.destroy');

        /**
         * Volunteers
         */
        Route::get('volunteers', [VolunteersController::class, 'index'])->name('dashboard.volunteers');
        Route::post('volunteers', [VolunteersController::class, 'store'])->name('dashboard.volunteers.store');
        Route::get('volunteers/{id}/edit', [VolunteersController::class, 'edit'])->name('dashboard.volunteers.edit');
        Route::post('volunteers/{id}', [VolunteersController::class, 'update'])->name('dashboard.volunteers.update');
        Route::delete('volunteers/{id}', [VolunteersController::class, 'destroy'])->name('dashboard.volunteers.destroy');

        Route::get('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'index'])->name('dashboard.volunteers.highlights');
        Route::post('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'store'])->name('dashboard.volunteers.highlights.store');
        Route::get('volunteers/{id}/highlights/{highlightId}/edit', [VolunteersHighlightsController::class, 'edit'])->name('dashboard.volunteers.highlights.edit');
        Route::post('volunteers/{id}/highlights/{highlightId}', [VolunteersHighlightsController::class, 'update'])->name('dashboard.volunteers.highlights.update');
        Route::delete('volunteers/{id}/highlights/{highlightId}', [VolunteersHighlightsController::class, 'destroy'])->name('dashboard.volunteers.highlights.destroy');

        /**
         * Education
         */
        Route::get('education', [EducationController::class, 'index'])->name('dashboard.education');
        Route::post('education', [EducationController::class, 'store'])->name('dashboard.education.store');
        Route::get('education/{id}/edit', [EducationController::class, 'edit'])->name('dashboard.education.edit');
        Route::post('education/{id}', [EducationController::class, 'update'])->name('dashboard.education.update');
        Route::delete('education/{id}', [EducationController::class, 'destroy'])->name('dashboard.education.destroy');

        /**
         * Awards
         */
        Route::get('awards', [AwardsController::class, 'index'])->name('dashboard.awards');
        Route::post('awards', [AwardsController::class, 'store'])->name('dashboard.awards.store');
        Route::get('awards/{id}/edit', [AwardsController::class, 'edit'])->name('dashboard.awards.edit');
        Route::post('awards/{id}', [AwardsController::class, 'update'])->name('dashboard.awards.update');
        Route::delete('awards/{id}', [AwardsController::class, 'destroy'])->name('dashboard.awards.destroy');

    });

require __DIR__.'/settings.php';
