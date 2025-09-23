<?php

namespace Database\Seeders;

use App\Models\AssetInvestment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetInvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $investmentsData = [
            ['id' => 1, 'asset_id' => 1, 'investment_type_id' => 1, 'actual_amount' => 179777.00, 'amount' => 7990.09, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 2, 'asset_id' => 1, 'investment_type_id' => 2, 'actual_amount' => 8205.00, 'amount' => 8205.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 3, 'asset_id' => 1, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 4, 'asset_id' => 2, 'investment_type_id' => 1, 'actual_amount' => 199777.00, 'amount' => 8878.98, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 5, 'asset_id' => 2, 'investment_type_id' => 2, 'actual_amount' => 9205.00, 'amount' => 9205.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 6, 'asset_id' => 2, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 7, 'asset_id' => 3, 'investment_type_id' => 1, 'actual_amount' => 219790.00, 'amount' => 9768.44, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 8, 'asset_id' => 3, 'investment_type_id' => 2, 'actual_amount' => 10228.00, 'amount' => 10228.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 9, 'asset_id' => 3, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 10, 'asset_id' => 4, 'investment_type_id' => 1, 'actual_amount' => 239788.00, 'amount' => 10657.24, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 11, 'asset_id' => 4, 'investment_type_id' => 2, 'actual_amount' => 11228.00, 'amount' => 11228.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 12, 'asset_id' => 4, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 13, 'asset_id' => 5, 'investment_type_id' => 1, 'actual_amount' => 104788.00, 'amount' => 4657.24, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 14, 'asset_id' => 5, 'investment_type_id' => 2, 'actual_amount' => 12228.00, 'amount' => 12228.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 15, 'asset_id' => 5, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 16, 'asset_id' => 6, 'investment_type_id' => 1, 'actual_amount' => 680323.00, 'amount' => 30236.58, 'currency' => 'INR', 'exchange_rate' => 22.500000, 'notes' => null],
            ['id' => 17, 'asset_id' => 6, 'investment_type_id' => 2, 'actual_amount' => 12228.00, 'amount' => 12228.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 18, 'asset_id' => 6, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 19, 'asset_id' => 7, 'investment_type_id' => 1, 'actual_amount' => 676305.00, 'amount' => 29404.57, 'currency' => 'INR', 'exchange_rate' => 23.000000, 'notes' => null],
            ['id' => 20, 'asset_id' => 7, 'investment_type_id' => 2, 'actual_amount' => 14270.00, 'amount' => 14270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 21, 'asset_id' => 7, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 22, 'asset_id' => 8, 'investment_type_id' => 1, 'actual_amount' => 706278.00, 'amount' => 30707.74, 'currency' => 'INR', 'exchange_rate' => 23.000000, 'notes' => null],
            ['id' => 23, 'asset_id' => 8, 'investment_type_id' => 2, 'actual_amount' => 15270.00, 'amount' => 15270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 24, 'asset_id' => 8, 'investment_type_id' => 3, 'actual_amount' => 400.00, 'amount' => 400.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 25, 'asset_id' => 9, 'investment_type_id' => 1, 'actual_amount' => 766275.00, 'amount' => 32607.45, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 26, 'asset_id' => 9, 'investment_type_id' => 2, 'actual_amount' => 17270.00, 'amount' => 17270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 27, 'asset_id' => 9, 'investment_type_id' => 3, 'actual_amount' => 2600.00, 'amount' => 2600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 28, 'asset_id' => 9, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 29, 'asset_id' => 10, 'investment_type_id' => 1, 'actual_amount' => 796275.00, 'amount' => 33884.04, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 30, 'asset_id' => 10, 'investment_type_id' => 2, 'actual_amount' => 18270.00, 'amount' => 18270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 31, 'asset_id' => 10, 'investment_type_id' => 3, 'actual_amount' => 3600.00, 'amount' => 3600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 32, 'asset_id' => 10, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 33, 'asset_id' => 11, 'investment_type_id' => 1, 'actual_amount' => 779426.00, 'amount' => 33167.06, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 34, 'asset_id' => 11, 'investment_type_id' => 2, 'actual_amount' => 19270.00, 'amount' => 19270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 35, 'asset_id' => 11, 'investment_type_id' => 3, 'actual_amount' => 4600.00, 'amount' => 4600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 36, 'asset_id' => 11, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 37, 'asset_id' => 11, 'investment_type_id' => 5, 'actual_amount' => 1000.00, 'amount' => 1000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 38, 'asset_id' => 12, 'investment_type_id' => 1, 'actual_amount' => 809426.00, 'amount' => 34443.66, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 39, 'asset_id' => 12, 'investment_type_id' => 2, 'actual_amount' => 20270.00, 'amount' => 20270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 40, 'asset_id' => 12, 'investment_type_id' => 3, 'actual_amount' => 4600.00, 'amount' => 4600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 41, 'asset_id' => 12, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 42, 'asset_id' => 12, 'investment_type_id' => 5, 'actual_amount' => 2000.00, 'amount' => 2000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 43, 'asset_id' => 13, 'investment_type_id' => 1, 'actual_amount' => 866270.00, 'amount' => 36862.55, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 44, 'asset_id' => 13, 'investment_type_id' => 2, 'actual_amount' => 21270.00, 'amount' => 21270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 45, 'asset_id' => 13, 'investment_type_id' => 3, 'actual_amount' => 6600.00, 'amount' => 6600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 46, 'asset_id' => 13, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 47, 'asset_id' => 13, 'investment_type_id' => 5, 'actual_amount' => 2000.00, 'amount' => 2000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 48, 'asset_id' => 14, 'investment_type_id' => 1, 'actual_amount' => 896269.00, 'amount' => 38139.11, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 49, 'asset_id' => 14, 'investment_type_id' => 2, 'actual_amount' => 22270.00, 'amount' => 22270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 50, 'asset_id' => 14, 'investment_type_id' => 3, 'actual_amount' => 6600.00, 'amount' => 6600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 51, 'asset_id' => 14, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 52, 'asset_id' => 15, 'investment_type_id' => 1, 'actual_amount' => 946266.00, 'amount' => 40266.64, 'currency' => 'INR', 'exchange_rate' => 23.500000, 'notes' => null],
            ['id' => 53, 'asset_id' => 15, 'investment_type_id' => 2, 'actual_amount' => 23270.00, 'amount' => 23270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 54, 'asset_id' => 15, 'investment_type_id' => 3, 'actual_amount' => 9600.00, 'amount' => 9600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 55, 'asset_id' => 15, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 56, 'asset_id' => 16, 'investment_type_id' => 1, 'actual_amount' => 976266.00, 'amount' => 40677.75, 'currency' => 'INR', 'exchange_rate' => 24.000000, 'notes' => null],
            ['id' => 57, 'asset_id' => 16, 'investment_type_id' => 2, 'actual_amount' => 24270.00, 'amount' => 24270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 58, 'asset_id' => 16, 'investment_type_id' => 3, 'actual_amount' => 10600.00, 'amount' => 10600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 59, 'asset_id' => 16, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 60, 'asset_id' => 17, 'investment_type_id' => 1, 'actual_amount' => 1006263.00, 'amount' => 41927.63, 'currency' => 'INR', 'exchange_rate' => 24.000000, 'notes' => null],
            ['id' => 61, 'asset_id' => 17, 'investment_type_id' => 2, 'actual_amount' => 25270.00, 'amount' => 25270.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 62, 'asset_id' => 17, 'investment_type_id' => 3, 'actual_amount' => 11600.00, 'amount' => 11600.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
            ['id' => 63, 'asset_id' => 17, 'investment_type_id' => 4, 'actual_amount' => 15000.00, 'amount' => 15000.00, 'currency' => 'QAR', 'exchange_rate' => 1.000000, 'notes' => null],
        ];

        foreach ($investmentsData as $investmentData) {
            AssetInvestment::updateOrCreate(
                ['id' => $investmentData['id']],
                [
                    'asset_id' => $investmentData['asset_id'],
                    'investment_type_id' => $investmentData['investment_type_id'],
                    'actual_amount' => $investmentData['actual_amount'],
                    'amount' => $investmentData['amount'],
                    'currency' => $investmentData['currency'],
                    'exchange_rate' => $investmentData['exchange_rate'],
                    'notes' => $investmentData['notes'],
                    'created_at' => '2025-09-22 12:37:27',
                    'updated_at' => '2025-09-22 12:37:27',
                ]
            );
        }
    }
}

