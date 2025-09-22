<?php

use App\Models\Asset;
use App\Models\User;

test('asset year is stored correctly as full 4-digit year', function () {
    $user = User::factory()->create();
    
    $asset = Asset::create([
        'user_id' => $user->id,
        'month' => 1,
        'year' => 2024,
        'notes' => 'Test year storage'
    ]);
    
    expect($asset->year)->toBe(2024);
    expect($asset->year)->toBeInt();
    
    // Verify it's stored correctly in database
    $assetFromDb = Asset::find($asset->id);
    expect($assetFromDb->year)->toBe(2024);
});

test('asset year handles different years correctly', function () {
    $user = User::factory()->create();
    
    $years = [2020, 2021, 2022, 2023, 2024, 2025];
    
    foreach ($years as $year) {
        $asset = Asset::create([
            'user_id' => $user->id,
            'month' => 1,
            'year' => $year,
            'notes' => "Test year {$year}"
        ]);
        
        expect($asset->year)->toBe($year);
        
        // Clean up
        $asset->delete();
    }
});
