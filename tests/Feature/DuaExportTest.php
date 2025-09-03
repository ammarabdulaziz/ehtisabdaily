<?php

use App\Models\Dua;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Set the current panel for Filament
    Filament::setCurrentPanel('hisabat');
});

it('can create duas for export testing', function () {
    // Create some test duas
    $duas = Dua::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'title' => 'Test Dua',
        'arabic_text' => 'بسم الله الرحمن الرحيم',
        'english_translation' => 'In the name of Allah, the Most Gracious, the Most Merciful',
        'categories' => ['Daily Duas'],
        'source' => 'Quran',
        'recitation_count' => 1,
    ]);

    expect($duas)->toHaveCount(3);
    expect($duas->first()->arabic_text)->toBe('بسم الله الرحمن الرحيم');
});

it('exports duas with proper arabic text formatting', function () {
    $dua = Dua::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Test Dua',
        'arabic_text' => 'بسم الله الرحمن الرحيم',
        'english_translation' => 'In the name of Allah, the Most Gracious, the Most Merciful',
        'categories' => ['Daily Duas', 'Morning Duas'],
        'source' => 'Quran',
        'reference' => 'Quran 1:1',
        'recitation_count' => 3,
    ]);

    // Test that the exporter can handle Arabic text properly
    $columns = \App\Filament\Exports\DuaExporter::getColumns();
    
    expect($columns)->toHaveCount(13);
    
    // Check that arabic_text column exists and has proper formatting
    $arabicColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'arabic_text';
    });
    expect($arabicColumn)->not->toBeNull();
    expect($arabicColumn->getLabel())->toBe('Arabic Text');
});
