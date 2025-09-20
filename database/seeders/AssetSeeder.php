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
        $users = User::all();
        
        foreach ($users as $user) {
            // Create assets for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->month;
                $year = $date->year;
                
                // Check if asset already exists for this month/year
                $asset = Asset::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'notes' => "Asset data for {$date->format('F Y')}",
                    ]
                );
                
                // Create asset accounts
                $this->createAssetAccounts($asset, $user);
                
                // Create lent money entries
                $this->createLentMoney($asset, $user);
                
                // Create borrowed money entries
                $this->createBorrowedMoney($asset, $user);
                
                // Create investments
                $this->createInvestments($asset, $user);
                
                // Create deposits
                $this->createDeposits($asset, $user);
            }
        }
    }
    
    private function createAssetAccounts(Asset $asset, User $user): void
    {
        $accountTypes = AccountType::where('user_id', $user->id)->get();
        
        foreach ($accountTypes as $accountType) {
            AssetAccount::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'account_type_id' => $accountType->id,
                ],
                [
                    'actual_amount' => fake()->randomFloat(2, 1000, 50000),
                    'currency' => fake()->randomElement(['QAR', 'USD', 'EUR']),
                    'exchange_rate' => $this->getExchangeRate(fake()->randomElement(['QAR', 'USD', 'EUR'])),
                    'notes' => fake()->optional()->sentence(),
                ]
            );
        }
    }
    
    private function createLentMoney(Asset $asset, User $user): void
    {
        $friends = Friend::where('user_id', $user->id)->get();
        
        // Create 1-3 lent money entries per asset
        $lentCount = fake()->numberBetween(1, 3);
        
        for ($i = 0; $i < $lentCount; $i++) {
            AssetLentMoney::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'friend_id' => $friends->random()->id,
                ],
                [
                    'actual_amount' => fake()->randomFloat(2, 500, 10000),
                    'currency' => fake()->randomElement(['QAR', 'USD', 'EUR']),
                    'exchange_rate' => $this->getExchangeRate(fake()->randomElement(['QAR', 'USD', 'EUR'])),
                    'notes' => fake()->optional()->sentence(),
                ]
            );
        }
    }
    
    private function createBorrowedMoney(Asset $asset, User $user): void
    {
        $friends = Friend::where('user_id', $user->id)->get();
        
        // Create 0-2 borrowed money entries per asset
        $borrowedCount = fake()->numberBetween(0, 2);
        
        for ($i = 0; $i < $borrowedCount; $i++) {
            AssetBorrowedMoney::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'friend_id' => $friends->random()->id,
                ],
                [
                    'actual_amount' => fake()->randomFloat(2, 1000, 15000),
                    'currency' => fake()->randomElement(['QAR', 'USD', 'EUR']),
                    'exchange_rate' => $this->getExchangeRate(fake()->randomElement(['QAR', 'USD', 'EUR'])),
                    'notes' => fake()->optional()->sentence(),
                ]
            );
        }
    }
    
    private function createInvestments(Asset $asset, User $user): void
    {
        $investmentTypes = InvestmentType::where('user_id', $user->id)->get();
        
        // Create 1-2 investment entries per asset
        $investmentCount = fake()->numberBetween(1, 2);
        
        for ($i = 0; $i < $investmentCount; $i++) {
            AssetInvestment::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'investment_type_id' => $investmentTypes->random()->id,
                ],
                [
                    'actual_amount' => fake()->randomFloat(2, 2000, 25000),
                    'currency' => fake()->randomElement(['QAR', 'USD', 'EUR']),
                    'exchange_rate' => $this->getExchangeRate(fake()->randomElement(['QAR', 'USD', 'EUR'])),
                    'notes' => fake()->optional()->sentence(),
                ]
            );
        }
    }
    
    private function createDeposits(Asset $asset, User $user): void
    {
        $depositTypes = DepositType::where('user_id', $user->id)->get();
        
        // Create 1-2 deposit entries per asset
        $depositCount = fake()->numberBetween(1, 2);
        
        for ($i = 0; $i < $depositCount; $i++) {
            AssetDeposit::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'deposit_type_id' => $depositTypes->random()->id,
                ],
                [
                    'actual_amount' => fake()->randomFloat(2, 5000, 30000),
                    'currency' => fake()->randomElement(['QAR', 'USD', 'EUR']),
                    'exchange_rate' => $this->getExchangeRate(fake()->randomElement(['QAR', 'USD', 'EUR'])),
                    'notes' => fake()->optional()->sentence(),
                ]
            );
        }
    }
    
    private function getExchangeRate(string $currency): float
    {
        return match ($currency) {
            'QAR' => 1.000000,
            'USD' => 3.640000,
            'EUR' => 3.950000,
            default => 1.000000,
        };
    }
}