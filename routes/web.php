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
    
    // Assets security routes (outside security middleware to allow access)
    Route::get('/assets/security', [App\Http\Controllers\AssetsSecurityController::class, 'show'])->name('assets.security');
    Route::post('/api/assets/verify-security', [App\Http\Controllers\AssetsSecurityController::class, 'verify'])->name('assets.verify-security');
    Route::post('/api/assets/toggle-lock', [App\Http\Controllers\AssetsSecurityController::class, 'toggleLock'])->name('assets.toggle-lock');
    Route::get('/api/assets/security-status', function () {
        return response()->json([
            'is_locked' => session('assets_security_locked', false),
            'has_valid_session' => session('assets_security_code') === '80313' && 
                                 session('assets_security_timestamp') && 
                                 (time() - session('assets_security_timestamp')) < 3600
        ]);
    })->name('assets.security-status');
    
    // Assets routes with security middleware
    Route::middleware(['assets.security'])->group(function () {
        Route::get('/assets', function () {
            return Inertia::render('Assets/Index');
        })->name('assets.index');

        // Asset chart API routes
        Route::get('/api/assets/chart-data', [AssetChartController::class, 'getChartData'])->name('assets.chart-data');
        Route::get('/api/assets/allocation-breakdown', [AssetChartController::class, 'getAllocationBreakdown'])->name('assets.allocation-breakdown');
        Route::get('/api/assets/lent-money-analysis', [AssetChartController::class, 'getLentMoneyAnalysis'])->name('assets.lent-money-analysis');
    });

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
