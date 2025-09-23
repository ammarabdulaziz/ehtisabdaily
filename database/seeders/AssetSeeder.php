<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetAccount;
use App\Models\AssetBorrowedMoney;
use App\Models\AssetDeposit;
use App\Models\AssetInvestment;
use App\Models\AssetLentMoney;
use App\Models\AccountType;
use App\Models\Friend;
use App\Models\InvestmentType;
use App\Models\DepositType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have the user
        $user = User::first();
        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        // Create all assets from SQL dump
        $this->createAssets($user);
        
        // Create all asset accounts
        $this->call(AssetAccountSeeder::class);
        
        // Create all asset investments
        $this->call(AssetInvestmentSeeder::class);
        
        // Create all asset deposits
        $this->call(AssetDepositSeeder::class);
        
        // Create all asset borrowed money
        $this->call(AssetBorrowedMoneySeeder::class);
        
        // Create all asset lent money
        $this->call(AssetLentMoneySeeder::class);
    }
    
    private function createAssets(User $user): void
    {
        $assetsData = [
            ['id' => 1, 'month' => 4, 'year' => 2024, 'notes' => null],
            ['id' => 2, 'month' => 5, 'year' => 2024, 'notes' => null],
            ['id' => 3, 'month' => 6, 'year' => 2024, 'notes' => null],
            ['id' => 4, 'month' => 7, 'year' => 2024, 'notes' => null],
            ['id' => 5, 'month' => 8, 'year' => 2024, 'notes' => null],
            ['id' => 6, 'month' => 9, 'year' => 2024, 'notes' => null],
            ['id' => 7, 'month' => 10, 'year' => 2024, 'notes' => null],
            ['id' => 8, 'month' => 11, 'year' => 2024, 'notes' => null],
            ['id' => 9, 'month' => 12, 'year' => 2024, 'notes' => null],
            ['id' => 10, 'month' => 2, 'year' => 2025, 'notes' => null],
            ['id' => 11, 'month' => 3, 'year' => 2025, 'notes' => null],
            ['id' => 12, 'month' => 4, 'year' => 2025, 'notes' => null],
            ['id' => 13, 'month' => 5, 'year' => 2025, 'notes' => null],
            ['id' => 14, 'month' => 6, 'year' => 2025, 'notes' => null],
            ['id' => 15, 'month' => 7, 'year' => 2025, 'notes' => null],
            ['id' => 16, 'month' => 8, 'year' => 2025, 'notes' => null],
            ['id' => 17, 'month' => 9, 'year' => 2025, 'notes' => null],
        ];

        foreach ($assetsData as $assetData) {
            Asset::updateOrCreate(
                ['id' => $assetData['id']],
                [
                    'user_id' => $user->id,
                    'month' => $assetData['month'],
                    'year' => $assetData['year'],
                    'notes' => $assetData['notes'],
                    'created_at' => '2025-09-22 12:37:27',
                    'updated_at' => '2025-09-22 12:37:27',
                ]
            );
        }
    }
    
}
