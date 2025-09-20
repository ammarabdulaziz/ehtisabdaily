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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Calculate the amount field for each related record
        $this->calculateAmounts($data);
        
        return $data;
    }

    private function calculateAmounts(array &$data): void
    {
        // Calculate amounts for accounts
        if (isset($data['accounts'])) {
            foreach ($data['accounts'] as &$account) {
                if (isset($account['actual_amount']) && isset($account['exchange_rate']) && $account['exchange_rate'] > 0) {
                    $account['amount'] = (float) $account['actual_amount'] / (float) $account['exchange_rate'];
                }
            }
        }

        // Calculate amounts for lent money
        if (isset($data['lentMoney'])) {
            foreach ($data['lentMoney'] as &$lent) {
                if (isset($lent['actual_amount']) && isset($lent['exchange_rate']) && $lent['exchange_rate'] > 0) {
                    $lent['amount'] = (float) $lent['actual_amount'] / (float) $lent['exchange_rate'];
                }
            }
        }

        // Calculate amounts for borrowed money
        if (isset($data['borrowedMoney'])) {
            foreach ($data['borrowedMoney'] as &$borrowed) {
                if (isset($borrowed['actual_amount']) && isset($borrowed['exchange_rate']) && $borrowed['exchange_rate'] > 0) {
                    $borrowed['amount'] = (float) $borrowed['actual_amount'] / (float) $borrowed['exchange_rate'];
                }
            }
        }

        // Calculate amounts for investments
        if (isset($data['investments'])) {
            foreach ($data['investments'] as &$investment) {
                if (isset($investment['actual_amount']) && isset($investment['exchange_rate']) && $investment['exchange_rate'] > 0) {
                    $investment['amount'] = (float) $investment['actual_amount'] / (float) $investment['exchange_rate'];
                }
            }
        }

        // Calculate amounts for deposits
        if (isset($data['deposits'])) {
            foreach ($data['deposits'] as &$deposit) {
                if (isset($deposit['actual_amount']) && isset($deposit['exchange_rate']) && $deposit['exchange_rate'] > 0) {
                    $deposit['amount'] = (float) $deposit['actual_amount'] / (float) $deposit['exchange_rate'];
                }
            }
        }
    }
}
