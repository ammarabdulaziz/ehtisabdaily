<?php

namespace App\Filament\Resources\Asset;

use App\Filament\Resources\Asset\Pages\CreateAsset;
use App\Filament\Resources\Asset\Pages\EditAsset;
use App\Filament\Resources\Asset\Pages\ListAsset;
use App\Filament\Resources\Asset\Pages\ViewAsset;
use App\Filament\Resources\Asset\RelationManagers\AccountsRelationManager;
use App\Filament\Resources\Asset\RelationManagers\BorrowedMoneyRelationManager;
use App\Filament\Resources\Asset\RelationManagers\DepositsRelationManager;
use App\Filament\Resources\Asset\RelationManagers\InvestmentsRelationManager;
use App\Filament\Resources\Asset\RelationManagers\LentMoneyRelationManager;
use App\Filament\Resources\Asset\Schemas\AssetForm;
use App\Filament\Resources\Asset\Tables\AssetTable;
use App\Filament\Schemas\AssetInfolist;
use App\Models\Asset;
use BackedEnum;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Assets';

    protected static UnitEnum|string|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'assets';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function form(Schema $schema): Schema
    {
        return AssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssetInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            AccountsRelationManager::class,
            LentMoneyRelationManager::class,
            BorrowedMoneyRelationManager::class,
            InvestmentsRelationManager::class,
            DepositsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAsset::route('/'),
            'create' => CreateAsset::route('/create'),
            'view' => ViewAsset::route('/{record}'),
            'edit' => EditAsset::route('/{record}/edit'),
        ];
    }
}
