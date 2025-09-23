<?php

use App\Models\Dua;
use App\Models\User;
use App\Services\DuaCacheService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // Clear cache before each test
    Cache::flush();
});

test('duas route uses cache for all duas', function () {
    $user = User::factory()->create();
    $duas = Dua::factory()->count(3)->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas']
    ]);
    
    // First request should hit database and populate cache
    $response = $this->actingAs($user)->get('/duas');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 3)
            ->has('categories')
    );
    
    // Verify cache is populated
    $cacheService = app(DuaCacheService::class);
    $cachedDuas = $cacheService->getAllDuasForUser($user->id);
    expect($cachedDuas)->toHaveCount(3);
    
    // Second request should use cache
    $response2 = $this->actingAs($user)->get('/duas');
    $response2->assertStatus(200);
    $response2->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 3)
    );
});

test('duas route uses cache for category filtering', function () {
    $user = User::factory()->create();
    $dailyDuas = Dua::factory()->count(2)->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas']
    ]);
    $eveningDuas = Dua::factory()->count(1)->create([
        'user_id' => $user->id,
        'categories' => ['Evening Duas']
    ]);
    
    // Request with category filter
    $response = $this->actingAs($user)->get('/duas?category=Daily Duas');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 2)
            ->where('currentCategory', 'Daily Duas')
    );
    
    // Verify category cache is populated
    $cacheService = app(DuaCacheService::class);
    $cachedDailyDuas = $cacheService->getDuasByCategory('Daily Duas', $user->id);
    expect($cachedDailyDuas)->toHaveCount(2);
    
    // Second request should use cache
    $response2 = $this->actingAs($user)->get('/duas?category=Daily Duas');
    $response2->assertStatus(200);
    $response2->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 2)
    );
});

test('duas route cache is invalidated when dua is created', function () {
    $user = User::factory()->create();
    
    // First request - no duas
    $response = $this->actingAs($user)->get('/duas');
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 0)
    );
    
    // Create a new dua
    Dua::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Dua'
    ]);
    
    // Second request should show the new dua (cache invalidated)
    $response2 = $this->actingAs($user)->get('/duas');
    $response2->assertStatus(200);
    $response2->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 1)
            ->where('duas.0.title', 'Test Dua')
    );
});

test('duas route cache is invalidated when dua is updated', function () {
    $user = User::factory()->create();
    $dua = Dua::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title'
    ]);
    
    // First request
    $response = $this->actingAs($user)->get('/duas');
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 1)
            ->where('duas.0.title', 'Original Title')
    );
    
    // Update the dua
    $dua->update(['title' => 'Updated Title']);
    
    // Second request should show updated data
    $response2 = $this->actingAs($user)->get('/duas');
    $response2->assertStatus(200);
    $response2->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 1)
            ->where('duas.0.title', 'Updated Title')
    );
});

test('duas route cache is invalidated when dua is deleted', function () {
    $user = User::factory()->create();
    $dua = Dua::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Dua'
    ]);
    
    // First request
    $response = $this->actingAs($user)->get('/duas');
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 1)
    );
    
    // Delete the dua
    $dua->delete();
    
    // Second request should show no duas
    $response2 = $this->actingAs($user)->get('/duas');
    $response2->assertStatus(200);
    $response2->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 0)
    );
});

test('duas route shows cached categories', function () {
    $user = User::factory()->create();
    Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas', 'Morning Duas']
    ]);
    Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Evening Duas', 'Daily Duas']
    ]);
    
    $response = $this->actingAs($user)->get('/duas');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('categories')
            ->where('categories.Daily Duas', 'Daily Duas')
            ->where('categories.Morning Duas', 'Morning Duas')
            ->where('categories.Evening Duas', 'Evening Duas')
    );
    
    // Verify categories are cached
    $cacheService = app(DuaCacheService::class);
    $cachedCategories = $cacheService->getCategoriesForUser($user->id);
    expect($cachedCategories)->toContain('Daily Duas', 'Morning Duas', 'Evening Duas');
});
