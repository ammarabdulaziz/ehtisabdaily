<?php

use App\Http\Controllers\AssetChartController;
use App\Http\Controllers\DuaController;
use App\Http\Controllers\GlobalSecurityController;
use App\Http\Controllers\MotivationalQuoteController;
use App\Http\Controllers\YouTubeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
    // return Inertia::render('welcome');
})->name('home');

// Homepage route
Route::get('/home', function () {
    return Inertia::render('Home');
})->name('homepage');

// Public routes
Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
})->name('terms-of-service');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/duas', [DuaController::class, 'index'])->name('duas.index');
    
    // Adhkar routes
    Route::get('/morning-adhkar', function () {
        return Inertia::render('MorningAdhkar/Index');
    })->name('morning-adhkar.index');
    
    Route::get('/evening-adhkar', function () {
        return Inertia::render('EveningAdhkar/Index');
    })->name('evening-adhkar.index');
    
    // Global security routes (outside security middleware to allow access)
    Route::get('/global-security', [GlobalSecurityController::class, 'show'])->name('global-security.show');
    Route::get('/api/global-security/status', [GlobalSecurityController::class, 'status'])->name('global-security.status');
    Route::post('/api/global-security/verify', [GlobalSecurityController::class, 'verify'])->name('global-security.verify');
    Route::post('/api/global-security/toggle', [GlobalSecurityController::class, 'toggleLock'])->name('global-security.toggle');
    
    // Legacy Assets security routes (for backward compatibility)
    Route::get('/assets/security', [App\Http\Controllers\AssetsSecurityController::class, 'show'])->name('assets.security');
    Route::post('/api/assets/verify-security', [App\Http\Controllers\AssetsSecurityController::class, 'verify'])->name('assets.verify-security');
    Route::post('/api/assets/toggle-lock', [App\Http\Controllers\AssetsSecurityController::class, 'toggleLock'])->name('assets.toggle-lock');
    Route::get('/api/assets/security-status', function () {
        return response()->json([
            'is_locked' => session('global_security_locked', false),
            'has_valid_session' => session('global_security_code') === '80313' && 
                                 session('global_security_timestamp') && 
                                 (time() - session('global_security_timestamp')) < 3600
        ]);
    })->name('assets.security-status');
    
    // Assets routes with global security middleware
    Route::middleware(['global.security'])->group(function () {
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
    
    // Motivational quotes API
    Route::post('/api/motivational-quote', [MotivationalQuoteController::class, 'generate'])->name('motivational-quote.generate');
    
    // YouTube main page and API routes
    Route::get('/my-youtube', [YouTubeController::class, 'index'])->name('youtube.index');
    Route::get('/test-youtube', [YouTubeController::class, 'testPage'])->name('youtube.test');
    Route::get('/api/youtube/watch-later', [YouTubeController::class, 'getWatchLater'])->name('youtube.watch-later');
    Route::get('/api/youtube/search', [YouTubeController::class, 'search'])->name('youtube.search');
    Route::post('/api/youtube/validate-search', [YouTubeController::class, 'validateSearchQuery'])->name('youtube.validate-search');
    Route::get('/api/youtube/playlists', [YouTubeController::class, 'getPlaylists'])->name('youtube.playlists');
    Route::get('/api/youtube/playlist/{playlistId}', [YouTubeController::class, 'getPlaylistVideos'])->name('youtube.playlist.videos');
    Route::post('/api/youtube/playlist/{playlistId}/add', [YouTubeController::class, 'addToPlaylist'])->name('youtube.playlist.add');
    Route::delete('/api/youtube/playlist/{playlistId}/item/{playlistItemId}', [YouTubeController::class, 'removeFromPlaylist'])->name('youtube.playlist.remove');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
