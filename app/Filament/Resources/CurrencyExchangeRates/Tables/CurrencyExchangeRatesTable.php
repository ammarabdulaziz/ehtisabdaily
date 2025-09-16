<?php

namespace App\Filament\Resources\CurrencyExchangeRates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CurrencyExchangeRatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fromCurrency.name')
                    ->label('From Currency')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('toCurrency.name')
                    ->label('To Currency')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rate')
                    ->label('Rate')
                    ->numeric(decimalPlaces: 6)
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('from_currency_id')
                    ->label('From Currency')
                    ->relationship('fromCurrency', 'name')
                    ->searchable(),
                SelectFilter::make('to_currency_id')
                    ->label('To Currency')
                    ->relationship('toCurrency', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
}
