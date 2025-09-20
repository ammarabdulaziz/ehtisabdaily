<?php

namespace App\Filament\Resources\Asset\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssetTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('formatted_period')
                    ->label('Period')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_accounts')
                    ->label('Total Accounts')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('total_lent_money')
                    ->label('Total Lent')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('total_borrowed_money')
                    ->label('Total Borrowed')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('total_investments')
                    ->label('Total Investments')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('total_deposits')
                    ->label('Total Deposits')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('total_in_hand')
                    ->label('Cash in Hand')
                    ->money('QAR')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('QAR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('savings')
                    ->label('Savings')
                    ->money('QAR')
                    ->sortable()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->options(range(2020, now()->year + 5))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $year): Builder => $query->where('year', $year),
                        );
                    }),
                SelectFilter::make('month')
                    ->options([
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $month): Builder => $query->where('month', $month),
                        );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('year', 'desc')
            ->defaultSort('month', 'desc');
    }
}
