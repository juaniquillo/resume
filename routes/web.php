<?php

use App\Http\Controllers\Auth\BasicsController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\VolunteersController;
use App\Http\Controllers\Auth\VolunteersControllerCreate;
use App\Http\Controllers\BasicsControllerCreate;
use App\Http\Controllers\BasicsLocationController;
use App\Http\Controllers\BasicsProfileController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkHighlightsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('basics', BasicsController::class)->name('dashboard.basics');
    Route::post('basics', BasicsControllerCreate::class)->name('dashboard.basics.edit');

    Route::get('volunteers', VolunteersController::class)->name('dashboard.volunteers');
    Route::post('volunteers', VolunteersControllerCreate::class)->name('dashboard.volunteers.edit');

    Route::get('basics/locations', [BasicsLocationController::class, 'index'])->name('dashboard.basics.locations');

    Route::get('basics/profiles', [BasicsProfileController::class, 'index'])->name('dashboard.basics.profiles');

    Route::get('works', [WorkController::class, 'index'])->name('dashboard.works');
    Route::get('works/highlights', [WorkHighlightsController::class, 'index'])->name('dashboard.works.highlights');

});

require __DIR__.'/settings.php';
