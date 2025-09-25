<?php

namespace App\Filament\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asset Information & Financial Summary')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                // Asset Information
                                TextEntry::make('formatted_period')
                                    ->label('Period'),
                                TextEntry::make('notes')
                                    ->label('Notes'),

                                // Financial Summary - Assets
                                TextEntry::make('total_accounts')
                                    ->label('Total Accounts')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),
                                TextEntry::make('total_investments')
                                    ->label('Total Investments')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),

                                // Financial Summary - Money Flow
                                TextEntry::make('total_lent_money')
                                    ->label('Total Lent Money')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),
                                TextEntry::make('total_deposits')
                                    ->label('Total Deposits')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),

                                // Financial Summary - Borrowed & Total
                                TextEntry::make('total_borrowed_money')
                                    ->label('Total Borrowed Money')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),
                                TextEntry::make('grand_total')
                                    ->label('Grand Total')
                                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0))
                                    ->weight('bold'),
                            ]),
                    ]),
            ]);
    }
}
