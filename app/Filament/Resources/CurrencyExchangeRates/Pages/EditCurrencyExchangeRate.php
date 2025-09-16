<?php

namespace App\Filament\Resources\CurrencyExchangeRates\Pages;

use App\Filament\Resources\CurrencyExchangeRates\CurrencyExchangeRateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCurrencyExchangeRate extends EditRecord
{
    protected static string $resource = CurrencyExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
