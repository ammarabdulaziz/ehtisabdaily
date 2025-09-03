<?php

namespace App\Filament\Exports;

use App\Models\Dua;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DuaExporter extends Exporter
{
    protected static ?string $model = Dua::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Title'),
            
            ExportColumn::make('arabic_text')
                ->label('Arabic Text')
                ->formatStateUsing(fn (string $state): string => $state),
            
            ExportColumn::make('transliteration')
                ->label('Transliteration'),
            
            ExportColumn::make('english_translation')
                ->label('English Translation'),
            
            ExportColumn::make('english_meaning')
                ->label('English Meaning'),
            
            ExportColumn::make('categories')
                ->label('Categories')
                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : ($state ?? 'No categories')),
            
            ExportColumn::make('source')
                ->label('Source'),
            
            ExportColumn::make('reference')
                ->label('Reference'),
            
            ExportColumn::make('benefits')
                ->label('Benefits'),
            
            ExportColumn::make('recitation_count')
                ->label('Recitation Count')
                ->formatStateUsing(fn (int $state): string => $state === 1 ? 'Once' : "{$state} times"),
            
            ExportColumn::make('sort_order')
                ->label('Sort Order'),
            
            ExportColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s')),
            
            ExportColumn::make('updated_at')
                ->label('Updated At')
                ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your dua export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
