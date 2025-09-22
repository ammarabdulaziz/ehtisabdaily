<?php

use App\Models\Asset;
use App\Models\User;
use Livewire\Livewire;
use App\Filament\Resources\Asset\Pages\CreateAsset;

test('filament form saves year correctly', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    // Test creating an asset through Filament
    $component = Livewire::test(CreateAsset::class)
        ->fillForm([
            'month' => 1,
            'year' => 2024,
            'notes' => 'Test year through Filament'
        ]);
    
    // Check form data before submission
    $formData = $component->instance()->form->getState();
    expect($formData['year'])->toBe(2024);
    
    $component->call('create');
    
    // Check if the asset was created with correct year
    $asset = Asset::where('user_id', $user->id)->latest()->first();
    
    expect($asset)->not->toBeNull();
    expect($asset->year)->toBe(2024);
    expect($asset->month)->toBe(1);
    
    // Clean up
    $asset->delete();
});

test('filament form handles different years correctly', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    $years = [2020, 2021, 2022, 2023, 2024, 2025];
    
    foreach ($years as $year) {
        Livewire::test(CreateAsset::class)
            ->fillForm([
                'month' => 1,
                'year' => $year,
                'notes' => "Test year {$year}"
            ])
            ->call('create')
            ->assertNotified();
        
        $asset = Asset::where('user_id', $user->id)->latest()->first();
        expect($asset->year)->toBe($year);
        
        // Clean up
        $asset->delete();
    }
});
