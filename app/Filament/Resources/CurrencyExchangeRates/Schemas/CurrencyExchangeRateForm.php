<?php

namespace App\Filament\Resources\CurrencyExchangeRates\Schemas;

use App\Models\Currency;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class CurrencyExchangeRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Select::make('from_currency_id')
                            ->label('From Currency')
                            ->options(Currency::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('to_currency_id')
                            ->label('To Currency')
                            ->options(Currency::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('rate')
                            ->label('Exchange Rate')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->step(0.000001),
                        DatePicker::make('date')
                            ->label('Date')
                            ->default(now())
                            ->required(),
                    ]),
                TextInput::make('source')
                    ->label('Source')
                    ->placeholder('e.g., Manual, API, Bank')
                    ->maxLength(255),
            ]);
    }
}
