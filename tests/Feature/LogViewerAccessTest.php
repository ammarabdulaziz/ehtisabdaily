<?php

use App\Models\User;

test('guests are redirected to login when accessing log-viewer', function () {
    $response = $this->get('/log-viewer');

    $response->assertRedirect('/login');
});

test('unauthorized users cannot access log-viewer', function () {
    $user = User::factory()->create([
        'email' => 'unauthorized@example.com',
    ]);

    $response = $this->actingAs($user)->get('/log-viewer');

    $response->assertStatus(403);
});

test('authorized user can access log-viewer', function () {
    $user = User::factory()->create([
        'email' => 'ammarabdulaziz99@gmail.com',
    ]);

    $response = $this->actingAs($user)->get('/log-viewer');

    $response->assertStatus(200);
});
