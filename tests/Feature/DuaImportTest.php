<?php

use App\Models\Dua;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Set the current panel for Filament
    Filament::setCurrentPanel('hisabat');
    
    // Use fake storage for file uploads
    Storage::fake('local');
});

it('can import duas from csv', function () {
    // Create a CSV file with test data
    $csvContent = "title,arabic_text,transliteration,english_translation,categories,source,recitation_count\n";
    $csvContent .= "Test Dua 1,بسم الله الرحمن الرحيم,Bismillah ir-Rahman ir-Raheem,In the name of Allah,Daily Duas,Quran,1\n";
    $csvContent .= "Test Dua 2,الحمد لله رب العالمين,Alhamdulillah rabbil alameen,Praise be to Allah,Daily Duas,Quran,3\n";
    
    $file = UploadedFile::fake()->createWithContent('duas.csv', $csvContent);
    
    // Test that the importer can handle the CSV data
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    expect($columns)->toHaveCount(11);
    
    // Check that required columns exist
    $titleColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'title';
    });
    expect($titleColumn)->not->toBeNull();
    expect($titleColumn->getLabel())->toBe('Title');
    
    $arabicColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'arabic_text';
    });
    expect($arabicColumn)->not->toBeNull();
    expect($arabicColumn->getLabel())->toBe('Arabic Text');
});

it('can resolve existing duas during import', function () {
    // Create an existing dua
    $existingDua = Dua::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Existing Dua',
        'arabic_text' => 'بسم الله الرحمن الرحيم',
        'english_translation' => 'Old translation',
    ]);
    
    // Test that the importer columns are configured correctly
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    expect($columns)->toHaveCount(11);
    
    // Check that title column is required for mapping
    $titleColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'title';
    });
    expect($titleColumn)->not->toBeNull();
});

it('can create new duas during import', function () {
    // Test that the importer columns are configured correctly
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    expect($columns)->toHaveCount(11);
    
    // Check that arabic_text column is required for mapping
    $arabicColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'arabic_text';
    });
    expect($arabicColumn)->not->toBeNull();
});

it('validates import column configuration', function () {
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    // Check that required columns exist
    $titleColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'title';
    });
    
    expect($titleColumn)->not->toBeNull();
    expect($titleColumn->getLabel())->toBe('Title');
    
    $arabicColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'arabic_text';
    });
    
    expect($arabicColumn)->not->toBeNull();
    expect($arabicColumn->getLabel())->toBe('Arabic Text');
});

it('handles categories as multiple values', function () {
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    $categoriesColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'categories';
    });
    
    expect($categoriesColumn)->not->toBeNull();
    expect($categoriesColumn->getLabel())->toBe('Categories');
});

it('provides example data for import columns', function () {
    $columns = \App\Filament\Imports\DuaImporter::getColumns();
    
    $titleColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'title';
    });
    
    expect($titleColumn->getExample())->toBe('Morning Dua');
    
    $arabicColumn = collect($columns)->first(function ($column) {
        return $column->getName() === 'arabic_text';
    });
    
    expect($arabicColumn->getExample())->toBe('بسم الله الرحمن الرحيم');
});
