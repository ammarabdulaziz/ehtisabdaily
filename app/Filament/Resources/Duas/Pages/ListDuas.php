<?php

namespace App\Filament\Resources\Duas\Pages;

use App\Filament\Resources\Duas\DuaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDuas extends ListRecords
{
    protected static string $resource = DuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
