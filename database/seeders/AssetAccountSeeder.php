<?php

namespace Database\Seeders;

use App\Models\AssetAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountsData = [
            ['id' => 1, 'asset_id' => 1, 'account_type_id' => 1, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 2, 'asset_id' => 1, 'account_type_id' => 2, 'actual_amount' => 22144.00, 'amount' => 22144.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 3, 'asset_id' => 1, 'account_type_id' => 3, 'actual_amount' => 39859.00, 'amount' => 39859.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 4, 'asset_id' => 1, 'account_type_id' => 4, 'actual_amount' => 1000.00, 'amount' => 1000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 5, 'asset_id' => 2, 'account_type_id' => 1, 'actual_amount' => 14800.00, 'amount' => 14800.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 6, 'asset_id' => 2, 'account_type_id' => 2, 'actual_amount' => 16851.00, 'amount' => 16851.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 7, 'asset_id' => 2, 'account_type_id' => 3, 'actual_amount' => 50226.00, 'amount' => 50226.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 8, 'asset_id' => 2, 'account_type_id' => 4, 'actual_amount' => 5080.00, 'amount' => 5080.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 9, 'asset_id' => 3, 'account_type_id' => 1, 'actual_amount' => 10000.00, 'amount' => 10000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 10, 'asset_id' => 3, 'account_type_id' => 2, 'actual_amount' => 13135.00, 'amount' => 13135.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 11, 'asset_id' => 3, 'account_type_id' => 3, 'actual_amount' => 60126.00, 'amount' => 60126.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 12, 'asset_id' => 3, 'account_type_id' => 4, 'actual_amount' => 72915.00, 'amount' => 3240.67, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 13, 'asset_id' => 4, 'account_type_id' => 1, 'actual_amount' => 9000.00, 'amount' => 9000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 14, 'asset_id' => 4, 'account_type_id' => 2, 'actual_amount' => 11503.00, 'amount' => 11503.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 15, 'asset_id' => 4, 'account_type_id' => 3, 'actual_amount' => 60765.00, 'amount' => 60765.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 16, 'asset_id' => 4, 'account_type_id' => 4, 'actual_amount' => 139006.00, 'amount' => 6178.04, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 17, 'asset_id' => 5, 'account_type_id' => 1, 'actual_amount' => 11350.00, 'amount' => 11350.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 18, 'asset_id' => 5, 'account_type_id' => 2, 'actual_amount' => 10456.00, 'amount' => 10456.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 19, 'asset_id' => 5, 'account_type_id' => 3, 'actual_amount' => 64248.00, 'amount' => 64248.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 20, 'asset_id' => 5, 'account_type_id' => 4, 'actual_amount' => 297207.00, 'amount' => 13209.20, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 21, 'asset_id' => 6, 'account_type_id' => 1, 'actual_amount' => 500.00, 'amount' => 500.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 22, 'asset_id' => 6, 'account_type_id' => 2, 'actual_amount' => 23816.00, 'amount' => 23816.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 23, 'asset_id' => 6, 'account_type_id' => 3, 'actual_amount' => 32602.00, 'amount' => 32602.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 24, 'asset_id' => 6, 'account_type_id' => 4, 'actual_amount' => 144393.00, 'amount' => 6417.47, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 25, 'asset_id' => 7, 'account_type_id' => 1, 'actual_amount' => 2170.00, 'amount' => 2170.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 26, 'asset_id' => 7, 'account_type_id' => 2, 'actual_amount' => 29725.00, 'amount' => 29725.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 27, 'asset_id' => 7, 'account_type_id' => 3, 'actual_amount' => 22606.00, 'amount' => 22606.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 28, 'asset_id' => 7, 'account_type_id' => 4, 'actual_amount' => 148715.00, 'amount' => 6465.87, 'currency' => 'INR', 'exchange_rate' => 23.000000, 'notes' => null],
            ['id' => 29, 'asset_id' => 8, 'account_type_id' => 1, 'actual_amount' => 600.00, 'amount' => 600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 30, 'asset_id' => 8, 'account_type_id' => 2, 'actual_amount' => 41061.00, 'amount' => 41061.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 31, 'asset_id' => 8, 'account_type_id' => 3, 'actual_amount' => 20633.00, 'amount' => 20633.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 32, 'asset_id' => 8, 'account_type_id' => 4, 'actual_amount' => 148307.00, 'amount' => 6448.13, 'currency' => 'INR', 'exchange_rate' => 23.000000, 'notes' => null],
            ['id' => 33, 'asset_id' => 9, 'account_type_id' => 1, 'actual_amount' => 2345.00, 'amount' => 2345.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 34, 'asset_id' => 9, 'account_type_id' => 2, 'actual_amount' => 32875.00, 'amount' => 32875.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 35, 'asset_id' => 9, 'account_type_id' => 3, 'actual_amount' => 24360.00, 'amount' => 24360.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 36, 'asset_id' => 9, 'account_type_id' => 4, 'actual_amount' => 155600.00, 'amount' => 6621.28, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 37, 'asset_id' => 10, 'account_type_id' => 1, 'actual_amount' => 1845.00, 'amount' => 1845.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 38, 'asset_id' => 10, 'account_type_id' => 2, 'actual_amount' => 44452.00, 'amount' => 44452.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 39, 'asset_id' => 10, 'account_type_id' => 3, 'actual_amount' => 16103.00, 'amount' => 16103.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 40, 'asset_id' => 10, 'account_type_id' => 4, 'actual_amount' => 355055.00, 'amount' => 15108.72, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 41, 'asset_id' => 11, 'account_type_id' => 1, 'actual_amount' => 1845.00, 'amount' => 1845.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 42, 'asset_id' => 11, 'account_type_id' => 2, 'actual_amount' => 58996.00, 'amount' => 58996.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 43, 'asset_id' => 11, 'account_type_id' => 3, 'actual_amount' => 10769.00, 'amount' => 10769.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 44, 'asset_id' => 11, 'account_type_id' => 4, 'actual_amount' => 413760.00, 'amount' => 17606.81, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 45, 'asset_id' => 12, 'account_type_id' => 1, 'actual_amount' => 7700.00, 'amount' => 7700.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 46, 'asset_id' => 12, 'account_type_id' => 2, 'actual_amount' => 25948.00, 'amount' => 25948.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 47, 'asset_id' => 12, 'account_type_id' => 3, 'actual_amount' => 16697.00, 'amount' => 16697.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 48, 'asset_id' => 12, 'account_type_id' => 4, 'actual_amount' => 363517.00, 'amount' => 15468.81, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 49, 'asset_id' => 13, 'account_type_id' => 1, 'actual_amount' => 12800.00, 'amount' => 12800.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 50, 'asset_id' => 13, 'account_type_id' => 2, 'actual_amount' => 25586.00, 'amount' => 25586.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 51, 'asset_id' => 13, 'account_type_id' => 3, 'actual_amount' => 11777.00, 'amount' => 11777.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 52, 'asset_id' => 13, 'account_type_id' => 4, 'actual_amount' => 380982.00, 'amount' => 16212.00, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 53, 'asset_id' => 14, 'account_type_id' => 1, 'actual_amount' => 14350.00, 'amount' => 14350.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 54, 'asset_id' => 14, 'account_type_id' => 2, 'actual_amount' => 31756.00, 'amount' => 31756.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 55, 'asset_id' => 14, 'account_type_id' => 3, 'actual_amount' => 13199.00, 'amount' => 13199.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 56, 'asset_id' => 14, 'account_type_id' => 4, 'actual_amount' => 136606.00, 'amount' => 5813.02, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 57, 'asset_id' => 15, 'account_type_id' => 1, 'actual_amount' => 3200.00, 'amount' => 3200.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 58, 'asset_id' => 15, 'account_type_id' => 2, 'actual_amount' => 29109.00, 'amount' => 29109.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 59, 'asset_id' => 15, 'account_type_id' => 3, 'actual_amount' => 2108.00, 'amount' => 2108.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 60, 'asset_id' => 15, 'account_type_id' => 4, 'actual_amount' => 415624.00, 'amount' => 17686.13, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 61, 'asset_id' => 16, 'account_type_id' => 1, 'actual_amount' => 5500.00, 'amount' => 5500.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 62, 'asset_id' => 16, 'account_type_id' => 2, 'actual_amount' => 19000.00, 'amount' => 19000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 63, 'asset_id' => 16, 'account_type_id' => 3, 'actual_amount' => 9318.00, 'amount' => 9318.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 64, 'asset_id' => 16, 'account_type_id' => 4, 'actual_amount' => 291530.00, 'amount' => 12147.08, 'currency' => 'INR', 'exchange_rate' => 24.000000, 'notes' => null],
            ['id' => 65, 'asset_id' => 17, 'account_type_id' => 1, 'actual_amount' => 100.00, 'amount' => 100.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 66, 'asset_id' => 17, 'account_type_id' => 2, 'actual_amount' => 24500.00, 'amount' => 24500.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 67, 'asset_id' => 17, 'account_type_id' => 3, 'actual_amount' => 2794.00, 'amount' => 2794.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 68, 'asset_id' => 17, 'account_type_id' => 4, 'actual_amount' => 329040.00, 'amount' => 13710.00, 'currency' => 'INR', 'exchange_rate' => 24.000000, 'notes' => null],
        ];

        foreach ($accountsData as $accountData) {
            AssetAccount::updateOrCreate(
                ['id' => $accountData['id']],
                [
                    'asset_id' => $accountData['asset_id'],
                    'account_type_id' => $accountData['account_type_id'],
                    'actual_amount' => $accountData['actual_amount'],
                    'amount' => $accountData['amount'],
                    'currency' => $accountData['currency'],
                    'exchange_rate' => $accountData['exchange_rate'],
                    'notes' => $accountData['notes'],
                    'created_at' => '2025-09-22 12:37:27',
                    'updated_at' => '2025-09-22 12:37:27',
                ]
            );
        }
    }
}

