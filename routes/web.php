<?php

use App\Http\Controllers\DuaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/duas', [DuaController::class, 'index'])->name('duas.index');
    Route::get('/assets', function () {
        return Inertia::render('Assets/Index');
    })->name('assets.index');

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
