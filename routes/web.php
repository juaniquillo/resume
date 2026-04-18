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

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('basics', BasicsController::class)->name('dashboard.basics');
    Route::post('basics', BasicsCreateController::class)->name('dashboard.basics.update');

    Route::get('works', [WorkController::class, 'index'])->name('dashboard.works');
    Route::post('works', [WorkController::class, 'store'])->name('dashboard.works.store');
    Route::get('works/{id}/highlights', [WorkHighlightsController::class, 'index'])->name('dashboard.works.highlights');

    Route::get('volunteers', [VolunteersController::class, 'index'])->name('dashboard.volunteers');
    Route::post('volunteers', [VolunteersController::class, 'store'])->name('dashboard.volunteers.store');
    Route::get('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'index'])->name('dashboard.volunteers.highlights');

    Route::get('education', [EducationController::class, 'index'])->name('dashboard.education');
    Route::post('education', [EducationController::class, 'store'])->name('dashboard.education.store');

    Route::get('awards', [AwardsController::class, 'index'])->name('dashboard.awards');
    Route::post('awards', [AwardsController::class, 'store'])->name('dashboard.awards.store');

    Route::get('basics/locations', [BasicsLocationController::class, 'index'])->name('dashboard.basics.locations');

    Route::get('basics/profiles', [BasicsProfileController::class, 'index'])->name('dashboard.basics.profiles');

});

require __DIR__.'/settings.php';
