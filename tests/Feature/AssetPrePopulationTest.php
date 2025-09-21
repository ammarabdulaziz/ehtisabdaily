<?php

use App\Models\Asset;
use App\Models\AssetAccount;
use App\Models\AssetLentMoney;
use App\Models\AssetBorrowedMoney;
use App\Models\AssetInvestment;
use App\Models\AssetDeposit;
use App\Models\AccountType;
use App\Models\Friend;
use App\Models\InvestmentType;
use App\Models\DepositType;
use App\Models\User;

it('can get previous month asset data', function () {
    $user = User::factory()->create();
    
    // Create account type, friend, investment type, and deposit type
    $accountType = AccountType::factory()->create(['user_id' => $user->id]);
    $friend = Friend::factory()->create(['user_id' => $user->id]);
    $investmentType = InvestmentType::factory()->create(['user_id' => $user->id]);
    $depositType = DepositType::factory()->create(['user_id' => $user->id]);
    
    // Create previous month asset (December 2023)
    $previousAsset = Asset::factory()->create([
        'user_id' => $user->id,
        'month' => 12,
        'year' => 2023,
        'notes' => 'Previous month data',
    ]);
    
    // Create related data for previous month
    AssetAccount::factory()->create([
        'asset_id' => $previousAsset->id,
        'account_type_id' => $accountType->id,
        'actual_amount' => 1000.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Previous account',
    ]);
    
    AssetLentMoney::factory()->create([
        'asset_id' => $previousAsset->id,
        'friend_id' => $friend->id,
        'actual_amount' => 500.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Previous loan',
    ]);
    
    AssetInvestment::factory()->create([
        'asset_id' => $previousAsset->id,
        'investment_type_id' => $investmentType->id,
        'actual_amount' => 2000.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Previous investment',
    ]);
    
    AssetDeposit::factory()->create([
        'asset_id' => $previousAsset->id,
        'deposit_type_id' => $depositType->id,
        'actual_amount' => 1500.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Previous deposit',
    ]);
    
    // Test getting previous month data for January 2024
    $previousData = Asset::getPreviousMonthData($user->id, 1, 2024);
    
    expect($previousData)->not->toBeNull();
    expect($previousData->month)->toBe(12);
    expect($previousData->year)->toBe(2023);
    expect($previousData->notes)->toBe('Previous month data');
    expect($previousData->accounts)->toHaveCount(1);
    expect($previousData->lentMoney)->toHaveCount(1);
    expect($previousData->investments)->toHaveCount(1);
    expect($previousData->deposits)->toHaveCount(1);
});

it('returns null when no previous month data exists', function () {
    $user = User::factory()->create();
    
    // Test getting previous month data when none exists
    $previousData = Asset::getPreviousMonthData($user->id, 1, 2024);
    
    expect($previousData)->toBeNull();
});

it('can generate form data for pre-population', function () {
    $user = User::factory()->create();
    
    // Create account type, friend, investment type, and deposit type
    $accountType = AccountType::factory()->create(['user_id' => $user->id]);
    $friend = Friend::factory()->create(['user_id' => $user->id]);
    $investmentType = InvestmentType::factory()->create(['user_id' => $user->id]);
    $depositType = DepositType::factory()->create(['user_id' => $user->id]);
    
    // Create asset with related data
    $asset = Asset::factory()->create([
        'user_id' => $user->id,
        'month' => 12,
        'year' => 2023,
        'notes' => 'Test asset',
    ]);
    
    // Create related data
    AssetAccount::factory()->create([
        'asset_id' => $asset->id,
        'account_type_id' => $accountType->id,
        'actual_amount' => 1000.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Test account',
    ]);
    
    AssetLentMoney::factory()->create([
        'asset_id' => $asset->id,
        'friend_id' => $friend->id,
        'actual_amount' => 500.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Test loan',
    ]);
    
    AssetInvestment::factory()->create([
        'asset_id' => $asset->id,
        'investment_type_id' => $investmentType->id,
        'actual_amount' => 2000.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Test investment',
    ]);
    
    AssetDeposit::factory()->create([
        'asset_id' => $asset->id,
        'deposit_type_id' => $depositType->id,
        'actual_amount' => 1500.00,
        'currency' => 'QAR',
        'exchange_rate' => 1.000000,
        'notes' => 'Test deposit',
    ]);
    
    // Generate form data
    $formData = $asset->getFormDataForPrePopulation();
    
    expect($formData)->toBeArray();
    expect($formData['user_id'])->toBe($user->id);
    expect($formData['month'])->toBe(12);
    expect($formData['year'])->toBe(2023);
    expect($formData['notes'])->toBe('Test asset');
    expect($formData['accounts'])->toHaveCount(1);
    expect($formData['lentMoney'])->toHaveCount(1);
    expect($formData['investments'])->toHaveCount(1);
    expect($formData['deposits'])->toHaveCount(1);
    
    // Check account data
    expect($formData['accounts'][0]['account_type_id'])->toBe($accountType->id);
    expect($formData['accounts'][0]['actual_amount'])->toBe('1000.00');
    expect($formData['accounts'][0]['currency'])->toBe('QAR');
    expect($formData['accounts'][0]['exchange_rate'])->toBe('1.000000');
    expect($formData['accounts'][0]['notes'])->toBe('Test account');
    
    // Check lent money data
    expect($formData['lentMoney'][0]['friend_id'])->toBe($friend->id);
    expect($formData['lentMoney'][0]['actual_amount'])->toBe('500.00');
    expect($formData['lentMoney'][0]['currency'])->toBe('QAR');
    expect($formData['lentMoney'][0]['exchange_rate'])->toBe('1.000000');
    expect($formData['lentMoney'][0]['notes'])->toBe('Test loan');
    
    // Check investment data
    expect($formData['investments'][0]['investment_type_id'])->toBe($investmentType->id);
    expect($formData['investments'][0]['actual_amount'])->toBe('2000.00');
    expect($formData['investments'][0]['currency'])->toBe('QAR');
    expect($formData['investments'][0]['exchange_rate'])->toBe('1.000000');
    expect($formData['investments'][0]['notes'])->toBe('Test investment');
    
    // Check deposit data
    expect($formData['deposits'][0]['deposit_type_id'])->toBe($depositType->id);
    expect($formData['deposits'][0]['actual_amount'])->toBe('1500.00');
    expect($formData['deposits'][0]['currency'])->toBe('QAR');
    expect($formData['deposits'][0]['exchange_rate'])->toBe('1.000000');
    expect($formData['deposits'][0]['notes'])->toBe('Test deposit');
});

it('handles year transition correctly for previous month calculation', function () {
    $user = User::factory()->create();
    
    // Create previous month asset (December 2023)
    $previousAsset = Asset::factory()->create([
        'user_id' => $user->id,
        'month' => 12,
        'year' => 2023,
    ]);
    
    // Test getting previous month data for January 2024 (should find December 2023)
    $previousData = Asset::getPreviousMonthData($user->id, 1, 2024);
    
    expect($previousData)->not->toBeNull();
    expect($previousData->month)->toBe(12);
    expect($previousData->year)->toBe(2023);
});

it('ensures user_id is properly set in pre-populated data', function () {
    $user = User::factory()->create();
    
    // Create asset with user_id
    $asset = Asset::factory()->create([
        'user_id' => $user->id,
        'month' => 12,
        'year' => 2023,
        'notes' => 'Test asset',
    ]);
    
    // Generate form data
    $formData = $asset->getFormDataForPrePopulation();
    
    // Verify user_id is included and not null
    expect($formData['user_id'])->not->toBeNull();
    expect($formData['user_id'])->toBe($user->id);
    expect($formData['user_id'])->toBeInt();
});