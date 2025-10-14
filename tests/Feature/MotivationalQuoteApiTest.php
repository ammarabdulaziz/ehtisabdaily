<?php

use App\Models\User;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    $this->actingAs($user);

    // Mock the GeminiService to avoid actual API calls during testing
    $this->mock(GeminiService::class, function ($mock) {
        $mock->shouldReceive('generateMotivationalQuote')
            ->andReturn([
                'quote' => 'Test motivational quote',
                'type' => 'general',
                'context' => 'Test context',
                'quranic_verse' => [
                    'arabic' => 'وَمَن يَتَوَكَّلْ عَلَى اللَّهِ فَهُوَ حَسْبُهُ',
                    'translation' => 'And whoever relies upon Allah - then He is sufficient for him.',
                    'reference' => 'At-Talaq (65:3)'
                ]
            ]);
    });
});

it('can generate motivational quote via API', function () {
    $response = $this->postJson('/api/motivational-quote', [
        'days_completed' => 10,
        'days_remaining' => 90,
        'percentage' => 10.0,
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'quote',
            'type',
            'context',
            'quranic_verse' => [
                'arabic',
                'translation',
                'reference'
            ]
        ])
        ->assertJson([
            'quote' => 'Test motivational quote',
            'type' => 'general',
            'context' => 'Test context',
            'quranic_verse' => [
                'arabic' => 'وَمَن يَتَوَكَّلْ عَلَى اللَّهِ فَهُوَ حَسْبُهُ',
                'translation' => 'And whoever relies upon Allah - then He is sufficient for him.',
                'reference' => 'At-Talaq (65:3)'
            ]
        ]);
});

it('validates required fields', function () {
    $response = $this->postJson('/api/motivational-quote', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['days_completed', 'days_remaining', 'percentage']);
});

it('validates field types', function () {
    $response = $this->postJson('/api/motivational-quote', [
        'days_completed' => 'not-a-number',
        'days_remaining' => -5,
        'percentage' => 150,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['days_completed', 'days_remaining', 'percentage']);
});

it('handles service unavailable when quote generation fails', function () {
    // Mock the GeminiService to return empty array (failure)
    $this->mock(GeminiService::class, function ($mock) {
        $mock->shouldReceive('generateMotivationalQuote')
            ->andReturn([]);
    });

    $response = $this->postJson('/api/motivational-quote', [
        'days_completed' => 10,
        'days_remaining' => 90,
        'percentage' => 10.0,
    ]);

    $response->assertStatus(503)
        ->assertJson([
            'error' => 'Unable to generate motivational quote at this time',
            'message' => 'Please try again later'
        ]);
});

it('handles validation errors properly', function () {
    $response = $this->postJson('/api/motivational-quote', [
        'days_completed' => 'invalid',
        'days_remaining' => -5,
        'percentage' => 150,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['days_completed', 'days_remaining', 'percentage']);
});