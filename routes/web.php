<?php

use App\Http\Controllers\AwardsController;
use App\Http\Controllers\AwardsCreateController;
use App\Http\Controllers\BasicsController;
use App\Http\Controllers\BasicsCreateController;
use App\Http\Controllers\BasicsLocationController;
use App\Http\Controllers\BasicsProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EducationControllerCreate;
use App\Http\Controllers\VolunteersController;
use App\Http\Controllers\VolunteersControllerCreate;
use App\Http\Controllers\VolunteersHighlightsController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkControllerCreate;
use App\Http\Controllers\WorkHighlightsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('basics', BasicsController::class)->name('dashboard.basics');
    Route::post('basics', BasicsCreateController::class)->name('dashboard.basics.edit');

    Route::get('works', [WorkController::class, 'index'])->name('dashboard.works');
    Route::post('works', WorkControllerCreate::class)->name('dashboard.works.edit');
    Route::get('works/{id}/highlights', [WorkHighlightsController::class, 'index'])->name('dashboard.works.highlights');

    Route::get('volunteers', VolunteersController::class)->name('dashboard.volunteers');
    Route::post('volunteers', VolunteersControllerCreate::class)->name('dashboard.volunteers.edit');
    Route::get('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'index'])->name('dashboard.volunteers.highlights');

    Route::get('education', EducationController::class)->name('dashboard.education');
    Route::post('education', EducationControllerCreate::class)->name('dashboard.education.edit');

    Route::get('awards', AwardsController::class)->name('dashboard.awards');
    Route::post('awards', AwardsCreateController::class)->name('dashboard.awards.edit');

    Route::get('basics/locations', [BasicsLocationController::class, 'index'])->name('dashboard.basics.locations');

    Route::get('basics/profiles', [BasicsProfileController::class, 'index'])->name('dashboard.basics.profiles');

});

require __DIR__.'/settings.php';
