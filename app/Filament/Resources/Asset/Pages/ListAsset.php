<?php

namespace App\Filament\Resources\Asset\Pages;

use App\Filament\Resources\Asset\AssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsset extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
