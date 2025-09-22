<?php

use App\Models\Asset;
use App\Models\AssetAccount;
use App\Models\AssetInvestment;
use App\Models\AssetDeposit;
use App\Models\AssetBorrowedMoney;
use App\Models\AssetLentMoney;
use App\Models\AccountType;
use App\Models\DepositType;
use App\Models\InvestmentType;
use App\Models\Friend;
use App\Models\User;

it('can soft delete and restore asset models', function () {
    $user = User::factory()->create();
    
    // Test Asset model
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    expect($asset->deleted_at)->toBeNull();
    
    $asset->delete();
    expect($asset->deleted_at)->not->toBeNull();
    expect(Asset::count())->toBe(0);
    expect(Asset::withTrashed()->count())->toBe(1);
    
    $asset->restore();
    expect($asset->deleted_at)->toBeNull();
    expect(Asset::count())->toBe(1);
});

it('can soft delete and restore asset account models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    $accountType = AccountType::factory()->create(['user_id' => $user->id]);
    
    $assetAccount = AssetAccount::factory()->create([
        'asset_id' => $asset->id,
        'account_type_id' => $accountType->id,
    ]);
    
    expect($assetAccount->deleted_at)->toBeNull();
    
    $assetAccount->delete();
    expect($assetAccount->deleted_at)->not->toBeNull();
    expect(AssetAccount::count())->toBe(0);
    expect(AssetAccount::withTrashed()->count())->toBe(1);
    
    $assetAccount->restore();
    expect($assetAccount->deleted_at)->toBeNull();
    expect(AssetAccount::count())->toBe(1);
});

it('can soft delete and restore asset investment models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    $investmentType = InvestmentType::factory()->create(['user_id' => $user->id]);
    
    $assetInvestment = AssetInvestment::factory()->create([
        'asset_id' => $asset->id,
        'investment_type_id' => $investmentType->id,
    ]);
    
    expect($assetInvestment->deleted_at)->toBeNull();
    
    $assetInvestment->delete();
    expect($assetInvestment->deleted_at)->not->toBeNull();
    expect(AssetInvestment::count())->toBe(0);
    expect(AssetInvestment::withTrashed()->count())->toBe(1);
    
    $assetInvestment->restore();
    expect($assetInvestment->deleted_at)->toBeNull();
    expect(AssetInvestment::count())->toBe(1);
});

it('can soft delete and restore asset deposit models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    $depositType = DepositType::factory()->create(['user_id' => $user->id]);
    
    $assetDeposit = AssetDeposit::factory()->create([
        'asset_id' => $asset->id,
        'deposit_type_id' => $depositType->id,
    ]);
    
    expect($assetDeposit->deleted_at)->toBeNull();
    
    $assetDeposit->delete();
    expect($assetDeposit->deleted_at)->not->toBeNull();
    expect(AssetDeposit::count())->toBe(0);
    expect(AssetDeposit::withTrashed()->count())->toBe(1);
    
    $assetDeposit->restore();
    expect($assetDeposit->deleted_at)->toBeNull();
    expect(AssetDeposit::count())->toBe(1);
});

it('can soft delete and restore asset borrowed money models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    $friend = Friend::factory()->create(['user_id' => $user->id]);
    
    $assetBorrowedMoney = AssetBorrowedMoney::factory()->create([
        'asset_id' => $asset->id,
        'friend_id' => $friend->id,
    ]);
    
    expect($assetBorrowedMoney->deleted_at)->toBeNull();
    
    $assetBorrowedMoney->delete();
    expect($assetBorrowedMoney->deleted_at)->not->toBeNull();
    expect(AssetBorrowedMoney::count())->toBe(0);
    expect(AssetBorrowedMoney::withTrashed()->count())->toBe(1);
    
    $assetBorrowedMoney->restore();
    expect($assetBorrowedMoney->deleted_at)->toBeNull();
    expect(AssetBorrowedMoney::count())->toBe(1);
});

it('can soft delete and restore asset lent money models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    $friend = Friend::factory()->create(['user_id' => $user->id]);
    
    $assetLentMoney = AssetLentMoney::factory()->create([
        'asset_id' => $asset->id,
        'friend_id' => $friend->id,
    ]);
    
    expect($assetLentMoney->deleted_at)->toBeNull();
    
    $assetLentMoney->delete();
    expect($assetLentMoney->deleted_at)->not->toBeNull();
    expect(AssetLentMoney::count())->toBe(0);
    expect(AssetLentMoney::withTrashed()->count())->toBe(1);
    
    $assetLentMoney->restore();
    expect($assetLentMoney->deleted_at)->toBeNull();
    expect(AssetLentMoney::count())->toBe(1);
});

it('can soft delete and restore reference models', function () {
    $user = User::factory()->create();
    
    // Test AccountType
    $accountType = AccountType::factory()->create(['user_id' => $user->id]);
    expect($accountType->deleted_at)->toBeNull();
    $accountType->delete();
    expect($accountType->deleted_at)->not->toBeNull();
    expect(AccountType::count())->toBe(0);
    expect(AccountType::withTrashed()->count())->toBe(1);
    $accountType->restore();
    expect($accountType->deleted_at)->toBeNull();
    expect(AccountType::count())->toBe(1);
    
    // Test DepositType
    $depositType = DepositType::factory()->create(['user_id' => $user->id]);
    expect($depositType->deleted_at)->toBeNull();
    $depositType->delete();
    expect($depositType->deleted_at)->not->toBeNull();
    expect(DepositType::count())->toBe(0);
    expect(DepositType::withTrashed()->count())->toBe(1);
    $depositType->restore();
    expect($depositType->deleted_at)->toBeNull();
    expect(DepositType::count())->toBe(1);
    
    // Test InvestmentType
    $investmentType = InvestmentType::factory()->create(['user_id' => $user->id]);
    expect($investmentType->deleted_at)->toBeNull();
    $investmentType->delete();
    expect($investmentType->deleted_at)->not->toBeNull();
    expect(InvestmentType::count())->toBe(0);
    expect(InvestmentType::withTrashed()->count())->toBe(1);
    $investmentType->restore();
    expect($investmentType->deleted_at)->toBeNull();
    expect(InvestmentType::count())->toBe(1);
    
    // Test Friend
    $friend = Friend::factory()->create(['user_id' => $user->id]);
    expect($friend->deleted_at)->toBeNull();
    $friend->delete();
    expect($friend->deleted_at)->not->toBeNull();
    expect(Friend::count())->toBe(0);
    expect(Friend::withTrashed()->count())->toBe(1);
    $friend->restore();
    expect($friend->deleted_at)->toBeNull();
    expect(Friend::count())->toBe(1);
});

it('can permanently delete soft deleted models', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create(['user_id' => $user->id]);
    
    $asset->delete();
    expect(Asset::withTrashed()->count())->toBe(1);
    
    $asset->forceDelete();
    expect(Asset::withTrashed()->count())->toBe(0);
});

it('can query only trashed models', function () {
    $user = User::factory()->create();
    $asset1 = Asset::factory()->create(['user_id' => $user->id]);
    $asset2 = Asset::factory()->create(['user_id' => $user->id]);
    
    $asset1->delete();
    
    expect(Asset::count())->toBe(1);
    expect(Asset::onlyTrashed()->count())->toBe(1);
    expect(Asset::withTrashed()->count())->toBe(2);
});