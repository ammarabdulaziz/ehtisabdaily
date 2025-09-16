<?php

namespace App\Filament\Resources\CurrencyExchangeRates\Pages;

use App\Filament\Resources\CurrencyExchangeRates\CurrencyExchangeRateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurrencyExchangeRates extends ListRecords
{
    protected static string $resource = CurrencyExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
