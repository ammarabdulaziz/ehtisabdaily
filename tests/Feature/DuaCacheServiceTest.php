<?php

use App\Models\Dua;
use App\Models\User;
use App\Services\DuaCacheService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // Clear cache before each test
    Cache::flush();
});

test('can cache all duas for user', function () {
    $user = User::factory()->create();
    $duas = Dua::factory()->count(3)->create(['user_id' => $user->id]);
    
    $cacheService = app(DuaCacheService::class);
    
    // First call should hit database
    $result = $cacheService->getAllDuasForUser($user->id);
    
    expect($result)->toHaveCount(3);
    expect($result->pluck('id')->toArray())->toEqualCanonicalizing($duas->pluck('id')->toArray());
    
    // Second call should hit cache
    $cachedResult = $cacheService->getAllDuasForUser($user->id);
    
    expect($cachedResult)->toHaveCount(3);
    expect($cachedResult->pluck('id')->toArray())->toEqualCanonicalizing($duas->pluck('id')->toArray());
});

test('can cache duas by category', function () {
    $user = User::factory()->create();
    $dua1 = Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas', 'Morning Duas']
    ]);
    $dua2 = Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Evening Duas']
    ]);
    
    $cacheService = app(DuaCacheService::class);
    
    // Get duas by category
    $dailyDuas = $cacheService->getDuasByCategory('Daily Duas', $user->id);
    
    expect($dailyDuas)->toHaveCount(1);
    expect($dailyDuas->first()->id)->toBe($dua1->id);
    
    // Second call should hit cache
    $cachedDailyDuas = $cacheService->getDuasByCategory('Daily Duas', $user->id);
    
    expect($cachedDailyDuas)->toHaveCount(1);
    expect($cachedDailyDuas->first()->id)->toBe($dua1->id);
});

test('can cache duas count', function () {
    $user = User::factory()->create();
    Dua::factory()->count(5)->create(['user_id' => $user->id]);
    
    $cacheService = app(DuaCacheService::class);
    
    // First call should hit database
    $count = $cacheService->getDuasCount($user->id);
    
    expect($count)->toBe(5);
    
    // Second call should hit cache
    $cachedCount = $cacheService->getDuasCount($user->id);
    
    expect($cachedCount)->toBe(5);
});

test('can cache categories for user', function () {
    $user = User::factory()->create();
    Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas', 'Morning Duas']
    ]);
    Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Evening Duas', 'Daily Duas']
    ]);
    
    $cacheService = app(DuaCacheService::class);
    
    // First call should hit database
    $categories = $cacheService->getCategoriesForUser($user->id);
    
    expect($categories)->toContain('Daily Duas', 'Morning Duas', 'Evening Duas');
    expect($categories)->toHaveCount(3);
    
    // Second call should hit cache
    $cachedCategories = $cacheService->getCategoriesForUser($user->id);
    
    expect($cachedCategories)->toContain('Daily Duas', 'Morning Duas', 'Evening Duas');
    expect($cachedCategories)->toHaveCount(3);
});

test('clears cache when dua is created', function () {
    $user = User::factory()->create();
    $cacheService = app(DuaCacheService::class);
    
    // Warm up cache
    $cacheService->warmUpUserCache($user->id);
    
    // Verify cache is populated
    $initialCount = $cacheService->getDuasCount($user->id);
    expect($initialCount)->toBe(0);
    
    // Create a new dua
    Dua::factory()->create(['user_id' => $user->id]);
    
    // Cache should be cleared and new count should be fetched
    $newCount = $cacheService->getDuasCount($user->id);
    expect($newCount)->toBe(1);
});

test('clears cache when dua is updated', function () {
    $user = User::factory()->create();
    $dua = Dua::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title'
    ]);
    
    $cacheService = app(DuaCacheService::class);
    
    // Warm up cache
    $cacheService->warmUpUserCache($user->id);
    
    // Update the dua
    $dua->update(['title' => 'Updated Title']);
    
    // Cache should be cleared and updated data should be fetched
    $cachedDuas = $cacheService->getAllDuasForUser($user->id);
    expect($cachedDuas->first()->title)->toBe('Updated Title');
});

test('clears cache when dua is deleted', function () {
    $user = User::factory()->create();
    $dua = Dua::factory()->create(['user_id' => $user->id]);
    
    $cacheService = app(DuaCacheService::class);
    
    // Warm up cache
    $cacheService->warmUpUserCache($user->id);
    
    // Verify initial count
    $initialCount = $cacheService->getDuasCount($user->id);
    expect($initialCount)->toBe(1);
    
    // Delete the dua
    $dua->delete();
    
    // Cache should be cleared and new count should be fetched
    $newCount = $cacheService->getDuasCount($user->id);
    expect($newCount)->toBe(0);
});

test('can clear user cache manually', function () {
    $user = User::factory()->create();
    Dua::factory()->count(3)->create(['user_id' => $user->id]);
    
    $cacheService = app(DuaCacheService::class);
    
    // Warm up cache
    $cacheService->warmUpUserCache($user->id);
    
    // Verify cache is populated
    $initialCount = $cacheService->getDuasCount($user->id);
    expect($initialCount)->toBe(3);
    
    // Clear cache manually
    $cacheService->clearUserCache($user->id);
    
    // Cache should be cleared and data should be fetched from database
    $newCount = $cacheService->getDuasCount($user->id);
    expect($newCount)->toBe(3);
});

test('can warm up user cache', function () {
    $user = User::factory()->create();
    Dua::factory()->count(2)->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas']
    ]);
    
    $cacheService = app(DuaCacheService::class);
    
    // Warm up cache
    $cacheService->warmUpUserCache($user->id);
    
    // All cached methods should return data without hitting database
    $duas = $cacheService->getAllDuasForUser($user->id);
    $count = $cacheService->getDuasCount($user->id);
    $categories = $cacheService->getCategoriesForUser($user->id);
    
    expect($duas)->toHaveCount(2);
    expect($count)->toBe(2);
    expect($categories)->toContain('Daily Duas');
});
