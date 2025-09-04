<?php

use App\Models\Dua;
use App\Models\User;

test('duas page loads successfully', function () {
    $response = $this->get('/duas');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas')
            ->has('categories')
    );
});

test('duas page filters by category', function () {
    $user = User::factory()->create();
    
    $dua1 = Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Daily Duas', 'Morning Duas'],
    ]);
    
    $dua2 = Dua::factory()->create([
        'user_id' => $user->id,
        'categories' => ['Evening Duas'],
    ]);

    $response = $this->get('/duas?category=Daily Duas');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 1)
            ->where('currentCategory', 'Daily Duas')
    );
});

test('duas page shows all duas when no category filter', function () {
    $user = User::factory()->create();
    
    Dua::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->get('/duas');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => 
        $page->component('Duas/Index')
            ->has('duas', 3)
            ->where('currentCategory', null)
    );
});
