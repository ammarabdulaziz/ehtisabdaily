<?php

namespace App\Filament\Resources\Asset;

use App\Filament\Resources\Asset\Pages\CreateAsset;
use App\Filament\Resources\Asset\Pages\EditAsset;
use App\Filament\Resources\Asset\Pages\ListAsset;
use App\Filament\Resources\Asset\Schemas\AssetForm;
use App\Filament\Resources\Asset\Tables\AssetTable;
use App\Models\Asset;
use BackedEnum;
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAsset::route('/'),
            'create' => CreateAsset::route('/create'),
            'edit' => EditAsset::route('/{record}/edit'),
        ];
    }
}
