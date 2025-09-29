<?php

use App\Http\Controllers\AssetChartController;
use App\Http\Controllers\DuaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
    // return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/duas', [DuaController::class, 'index'])->name('duas.index');
    Route::get('/assets', function () {
        return Inertia::render('Assets/Index');
    })->name('assets.index');

    // Asset chart API routes
    Route::get('/api/assets/chart-data', [AssetChartController::class, 'getChartData'])->name('assets.chart-data');
    Route::get('/api/assets/allocation-breakdown', [AssetChartController::class, 'getAllocationBreakdown'])->name('assets.allocation-breakdown');
    Route::get('/api/assets/lent-money-analysis', [AssetChartController::class, 'getLentMoneyAnalysis'])->name('assets.lent-money-analysis');

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
