<?php

namespace App\Filament\Resources\AssetManagement\Pages;

use App\Filament\Resources\AssetManagement\AssetManagementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetManagement extends EditRecord
{
    protected static string $resource = AssetManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
