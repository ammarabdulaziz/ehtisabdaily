<?php

use App\Models\User;
use Filament\Facades\Filament;

test('log viewer menu is visible to authorized user', function () {
    $user = User::factory()->create([
        'email' => 'ammarabdulaziz99@gmail.com',
    ]);

    $this->actingAs($user);

    Filament::setCurrentPanel('hisabat');

    $response = $this->get('/hisabat');

    $response->assertStatus(200);
    $response->assertSee('Log Viewer');
});

test('log viewer menu is not visible to unauthorized user', function () {
    $user = User::factory()->create([
        'email' => 'unauthorized@example.com',
    ]);

    $this->actingAs($user);

    Filament::setCurrentPanel('hisabat');

    $response = $this->get('/hisabat');

    $response->assertStatus(200);
    $response->assertDontSee('Log Viewer');
});

test('log viewer menu is not visible to guests', function () {
    Filament::setCurrentPanel('hisabat');

    $response = $this->get('/hisabat');

    $response->assertRedirect('/hisabat/login');
});
