<?php

use App\Services\GeminiService;

it('can be instantiated', function () {
    $service = new GeminiService;
    expect($service)->toBeInstanceOf(GeminiService::class);
});

it('builds correct prompt for dua glimpse', function () {
    $service = new GeminiService;
    $glimpse = 'Dua before eating';

    // Use reflection to test private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('buildPrompt');
    $method->setAccessible(true);

    $prompt = $method->invoke($service, $glimpse);

    expect($prompt)
        ->toContain('Dua before eating')
        ->toContain('title')
        ->toContain('arabic_text')
        ->toContain('english_translation')
        ->toContain('JSON format');
});

it('sanitizes data correctly', function () {
    $service = new GeminiService;

    // Use reflection to test private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('sanitizeData');
    $method->setAccessible(true);

    $rawData = [
        'title' => 'Test Dua',
        'arabic_text' => 'بِسْمِ اللَّهِ',
        'english_translation' => 'In the name of Allah',
        'recitation_count' => '3',
        'categories' => ['Daily', 'Food'],
        'occasions' => ['Before eating'],
        'tags' => ['blessing', 'food'],
    ];

    $sanitized = $method->invoke($service, $rawData);

    expect($sanitized)
        ->toHaveKey('title', 'Test Dua')
        ->toHaveKey('arabic_text', 'بِسْمِ اللَّهِ')
        ->toHaveKey('english_translation', 'In the name of Allah')
        ->toHaveKey('recitation_count', 3) // Should be integer
        ->toHaveKey('categories', ['Daily', 'Food'])
        ->toHaveKey('occasions', ['Before eating'])
        ->toHaveKey('tags', ['blessing', 'food']);
});

it('handles missing data gracefully', function () {
    $service = new GeminiService;

    // Use reflection to test private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('sanitizeData');
    $method->setAccessible(true);

    $rawData = [
        'title' => 'Test Dua',
        // Missing other fields
    ];

    $sanitized = $method->invoke($service, $rawData);

    expect($sanitized)
        ->toHaveKey('title', 'Test Dua')
        ->toHaveKey('arabic_text', '')
        ->toHaveKey('english_translation', '')
        ->toHaveKey('recitation_count', 1) // Default value
        ->toHaveKey('categories', [])
        ->toHaveKey('occasions', [])
        ->toHaveKey('tags', []);
});

it('validates recitation count range', function () {
    $service = new GeminiService;

    // Use reflection to test private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('sanitizeData');
    $method->setAccessible(true);

    $rawData = [
        'title' => 'Test Dua',
        'recitation_count' => 999, // Invalid value
    ];

    $sanitized = $method->invoke($service, $rawData);

    expect($sanitized['recitation_count'])->toBe(1); // Should default to 1
});

it('handles invalid recitation count types', function () {
    $service = new GeminiService;

    // Use reflection to test private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('sanitizeData');
    $method->setAccessible(true);

    $rawData = [
        'title' => 'Test Dua',
        'recitation_count' => 'invalid', // Non-numeric value
    ];

    $sanitized = $method->invoke($service, $rawData);

    expect($sanitized['recitation_count'])->toBe(1); // Should default to 1
});
