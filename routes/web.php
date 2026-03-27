<?php

use App\Http\Controllers\Auth\BasicsController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\BasicsControllerCreate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('basics', BasicsController::class)->name('dashboard.basics');
    Route::post('basics', BasicsControllerCreate::class)->name('dashboard.basics.edit');

});

require __DIR__.'/settings.php';
