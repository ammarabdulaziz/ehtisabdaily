<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Currency Code')
                            ->placeholder('e.g., USD, EUR, QAR')
                            ->required()
                            ->maxLength(3)
                            ->unique('currencies', 'code', ignoreRecord: true)
                            ->live()
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('code', strtoupper($state ?? ''))),
                        TextInput::make('name')
                            ->label('Currency Name')
                            ->placeholder('e.g., US Dollar, Euro, Qatari Riyal')
                            ->required()
                            ->maxLength(255),
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('symbol')
                            ->label('Currency Symbol')
                            ->placeholder('e.g., $, €, ر.ق')
                            ->maxLength(10),
                        Toggle::make('is_base')
                            ->label('Is Base Currency')
                            ->helperText('Only one currency can be the base currency'),
                    ]),
            ]);
    }
}
