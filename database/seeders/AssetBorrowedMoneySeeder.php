<?php

namespace Database\Seeders;

use App\Models\AssetBorrowedMoney;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetBorrowedMoneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $borrowedMoneyData = [
            ['id' => 1, 'asset_id' => 5, 'friend_id' => 4, 'actual_amount' => 683.00, 'amount' => 683.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 2, 'asset_id' => 7, 'friend_id' => 15, 'actual_amount' => 1850.00, 'amount' => 1850.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 3, 'asset_id' => 7, 'friend_id' => 4, 'actual_amount' => 2747.00, 'amount' => 2747.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 4, 'asset_id' => 7, 'friend_id' => 10, 'actual_amount' => 3000.00, 'amount' => 3000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 5, 'asset_id' => 8, 'friend_id' => 15, 'actual_amount' => 1850.00, 'amount' => 1850.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 6, 'asset_id' => 9, 'friend_id' => 10, 'actual_amount' => 6000.00, 'amount' => 6000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 7, 'asset_id' => 10, 'friend_id' => 10, 'actual_amount' => 3635.00, 'amount' => 3635.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 8, 'asset_id' => 10, 'friend_id' => 4, 'actual_amount' => 26.00, 'amount' => 26.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 9, 'asset_id' => 11, 'friend_id' => 15, 'actual_amount' => 416.00, 'amount' => 416.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 10, 'asset_id' => 11, 'friend_id' => 10, 'actual_amount' => 6635.00, 'amount' => 6635.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 11, 'asset_id' => 15, 'friend_id' => 10, 'actual_amount' => 2500.00, 'amount' => 2500.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
        ];

        foreach ($borrowedMoneyData as $borrowedData) {
            AssetBorrowedMoney::updateOrCreate(
                ['id' => $borrowedData['id']],
                [
                    'asset_id' => $borrowedData['asset_id'],
                    'friend_id' => $borrowedData['friend_id'],
                    'actual_amount' => $borrowedData['actual_amount'],
                    'amount' => $borrowedData['amount'],
                    'currency' => $borrowedData['currency'],
                    'exchange_rate' => $borrowedData['exchange_rate'],
                    'notes' => $borrowedData['notes'],
                    'created_at' => '2025-09-22 13:30:47',
                    'updated_at' => '2025-09-22 13:30:47',
                ]
            );
        }
    }
}
