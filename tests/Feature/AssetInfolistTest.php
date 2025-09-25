<?php

use App\Models\Asset;
use App\Models\User;
use App\Models\AccountType;
use App\Models\AssetAccount;
use App\Models\Friend;
use App\Models\AssetLentMoney;
use App\Models\InvestmentType;
use App\Models\AssetInvestment;
use App\Models\DepositType;
use App\Models\AssetDeposit;
use App\Models\AssetBorrowedMoney;
use App\Filament\Resources\Asset\Pages\ViewAsset;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Create related models
    $accountType = AccountType::factory()->create(['user_id' => $this->user->id]);
    $friend = Friend::factory()->create(['user_id' => $this->user->id]);
    $investmentType = InvestmentType::factory()->create();
    $depositType = DepositType::factory()->create();
    
    // Create asset with relationships
    $this->asset = Asset::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
        'notes' => 'Test asset for infolist',
    ]);
    
    // Create related records
    AssetAccount::factory()->create([
        'asset_id' => $this->asset->id,
        'account_type_id' => $accountType->id,
        'actual_amount' => 1000,
        'currency' => 'USD',
        'exchange_rate' => 3.64,
    ]);
    
    AssetLentMoney::factory()->create([
        'asset_id' => $this->asset->id,
        'friend_id' => $friend->id,
        'actual_amount' => 500,
        'currency' => 'USD',
        'exchange_rate' => 3.64,
    ]);
    
    AssetBorrowedMoney::factory()->create([
        'asset_id' => $this->asset->id,
        'friend_id' => $friend->id,
        'actual_amount' => 200,
        'currency' => 'USD',
        'exchange_rate' => 3.64,
    ]);
    
    AssetInvestment::factory()->create([
        'asset_id' => $this->asset->id,
        'investment_type_id' => $investmentType->id,
        'actual_amount' => 1000,
        'currency' => 'USD',
        'exchange_rate' => 3.64,
    ]);
    
    AssetDeposit::factory()->create([
        'asset_id' => $this->asset->id,
        'deposit_type_id' => $depositType->id,
        'actual_amount' => 5000,
        'currency' => 'USD',
        'exchange_rate' => 3.64,
    ]);
});

test('can view asset infolist', function () {
    Livewire::test(ViewAsset::class, ['record' => $this->asset->id])
        ->assertOk()
        ->assertSee('January 2025')
        ->assertSee('Test asset for infolist')
        ->assertSee('QAR 275') // Total accounts (1000/3.64)
        ->assertSee('QAR 137') // Total lent money (500/3.64)
        ->assertSee('QAR 55')  // Total borrowed money (200/3.64)
        ->assertSee('QAR 275') // Total investments (1000/3.64)
        ->assertSee('QAR 1,374'); // Total deposits (5000/3.64)
});

test('can see relation managers in stacked layout', function () {
    Livewire::test(ViewAsset::class, ['record' => $this->asset->id])
        ->assertOk()
        ->assertSee('Accounts')
        ->assertSee('Lent Money')
        ->assertSee('Borrowed Money')
        ->assertSee('Investments')
        ->assertSee('Deposits');
});

test('can see asset data in view page', function () {
    Livewire::test(ViewAsset::class, ['record' => $this->asset->id])
        ->assertOk()
        ->assertSee($this->asset->formatted_period)
        ->assertSee($this->asset->notes);
});