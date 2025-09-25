<?php

namespace App\Filament\Resources\Asset\RelationManagers;

use App\Models\AssetLentMoney;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class LentMoneyRelationManager extends RelationManager
{
    protected static string $relationship = 'lentMoney';

    protected static ?string $title = 'Lent Money';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('friend_id')
                    ->relationship('friend', 'name')
                    ->required(),
                Forms\Components\TextInput::make('actual_amount')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->required(),
                Forms\Components\TextInput::make('exchange_rate')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('friend')
            ->columns([
                Tables\Columns\TextColumn::make('friend.name')
                    ->label('Friend'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (QAR)')
                    ->formatStateUsing(fn ($state) => 'QAR ' . number_format($state, 0)),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
