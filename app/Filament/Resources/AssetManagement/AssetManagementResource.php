<?php

namespace App\Filament\Resources\AssetManagement;

use App\Filament\Resources\AssetManagement\Pages\CreateAssetManagement;
use App\Filament\Resources\AssetManagement\Pages\EditAssetManagement;
use App\Filament\Resources\AssetManagement\Pages\ListAssetManagement;
use App\Filament\Resources\AssetManagement\Schemas\AssetManagementForm;
use App\Filament\Resources\AssetManagement\Tables\AssetManagementTable;
use App\Models\AssetManagement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AssetManagementResource extends Resource
{
    protected static ?string $model = AssetManagement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Asset Management';

    protected static UnitEnum|string|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function form(Schema $schema): Schema
    {
        return AssetManagementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetManagementTable::configure($table);
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
            'index' => ListAssetManagement::route('/'),
            'create' => CreateAssetManagement::route('/create'),
            'edit' => EditAssetManagement::route('/{record}/edit'),
        ];
    }
}
