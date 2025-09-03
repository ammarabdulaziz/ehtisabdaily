<?php

namespace App\Filament\Imports;

use App\Models\Dua;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Support\Number;

class DuaImporter extends Importer
{
    protected static ?string $model = Dua::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Morning Dua'),
            
            ImportColumn::make('arabic_text')
                ->label('Arabic Text')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->example('بسم الله الرحمن الرحيم'),
            
            ImportColumn::make('transliteration')
                ->label('Transliteration')
                ->rules(['nullable', 'string'])
                ->example('Bismillah ir-Rahman ir-Raheem'),
            
            ImportColumn::make('english_translation')
                ->label('English Translation')
                ->rules(['nullable', 'string'])
                ->example('In the name of Allah, the Most Gracious, the Most Merciful'),
            
            ImportColumn::make('english_meaning')
                ->label('English Meaning')
                ->rules(['nullable', 'string'])
                ->example('This is the opening verse of the Quran...'),
            
            ImportColumn::make('categories')
                ->label('Categories')
                ->rules(['nullable', 'array'])
                ->multiple(',')
                ->example('Daily Duas, Morning Duas'),
            
            ImportColumn::make('source')
                ->label('Source')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Quran'),
            
            ImportColumn::make('reference')
                ->label('Reference')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Quran 1:1'),
            
            ImportColumn::make('benefits')
                ->label('Benefits')
                ->rules(['nullable', 'string'])
                ->example('Reciting this dua brings blessings...'),
            
            ImportColumn::make('recitation_count')
                ->label('Recitation Count')
                ->integer()
                ->rules(['nullable', 'integer', 'min:1'])
                ->example('1'),
            
            ImportColumn::make('sort_order')
                ->label('Sort Order')
                ->integer()
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('1'),
        ];
    }

    public function resolveRecord(): ?Dua
    {
        // Get the current user ID
        $userId = Filament::auth()->id();
        
        // Try to find existing record by title and user_id
        $existingDua = Dua::where('title', $this->data['title'])
            ->where('user_id', $userId)
            ->first();
        
        if ($existingDua) {
            // Update existing record
            return $existingDua;
        }
        
        // Create new record
        $dua = new Dua();
        $dua->user_id = $userId;
        
        return $dua;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your dua import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
