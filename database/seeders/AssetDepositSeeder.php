<?php

namespace Database\Seeders;

use App\Models\AssetDeposit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $depositsData = [
            ['id' => 1, 'asset_id' => 1, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 2, 'asset_id' => 2, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 3, 'asset_id' => 3, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 4, 'asset_id' => 4, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 5, 'asset_id' => 5, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 6, 'asset_id' => 6, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 7, 'asset_id' => 7, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 8, 'asset_id' => 8, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 9, 'asset_id' => 9, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 10, 'asset_id' => 10, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 11, 'asset_id' => 11, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 12, 'asset_id' => 12, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 13, 'asset_id' => 13, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 14, 'asset_id' => 14, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 15, 'asset_id' => 15, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 16, 'asset_id' => 16, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 17, 'asset_id' => 17, 'deposit_type_id' => 1, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
        ];

        foreach ($depositsData as $depositData) {
            AssetDeposit::updateOrCreate(
                ['id' => $depositData['id']],
                [
                    'asset_id' => $depositData['asset_id'],
                    'deposit_type_id' => $depositData['deposit_type_id'],
                    'actual_amount' => $depositData['actual_amount'],
                    'amount' => $depositData['amount'],
                    'currency' => $depositData['currency'],
                    'exchange_rate' => $depositData['exchange_rate'],
                    'notes' => $depositData['notes'],
                    'created_at' => '2025-09-22 12:37:28',
                    'updated_at' => '2025-09-22 12:37:28',
                ]
            );
        }
    }
}

