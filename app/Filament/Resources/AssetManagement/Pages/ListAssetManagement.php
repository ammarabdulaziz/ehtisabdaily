<?php

namespace App\Filament\Resources\AssetManagement\Pages;

use App\Filament\Resources\AssetManagement\AssetManagementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetManagement extends ListRecords
{
    protected static string $resource = AssetManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
