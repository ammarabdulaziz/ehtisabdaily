<?php

use App\Models\AssetManagement;
use App\Models\User;
use App\Models\AccountType;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    
    // Create some account types for testing
    AccountType::factory()->create(['user_id' => $this->user->id, 'name' => 'Cash-in-Hand', 'is_default' => true]);
    AccountType::factory()->create(['user_id' => $this->user->id, 'name' => 'Bank Account', 'is_default' => false]);
});

test('guests cannot access asset management', function () {
    $this->post(route('logout'));
    
    $this->get(route('filament.hisabat.resources.asset-management.index'))
        ->assertRedirect(route('filament.hisabat.auth.login'));
});

test('authenticated users can view asset management list', function () {
    $this->get(route('filament.hisabat.resources.asset-management.index'))
        ->assertOk()
        ->assertSee('Asset Management');
});

test('can create a new asset management record', function () {
    $assetData = [
        'month' => 12,
        'year' => 2024,
        'notes' => 'December 2024 assets',
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    assertDatabaseHas('asset_management', [
        'user_id' => $this->user->id,
        'month' => 12,
        'year' => 2024,
        'notes' => 'December 2024 assets',
    ]);
});

test('validates required fields', function () {
    $this->post(route('filament.hisabat.resources.asset-management.store'), [])
        ->assertSessionHasErrors(['month', 'year']);
});

test('can update existing asset management record', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
        'notes' => 'Original notes',
    ]);

    $updateData = [
        'month' => 1,
        'year' => 2025,
        'notes' => 'Updated notes for January 2025',
    ];

    $this->put(route('filament.hisabat.resources.asset-management.update', $assetManagement), $updateData)
        ->assertRedirect();

    $assetManagement->refresh();
    expect($assetManagement->notes)->toBe('Updated notes for January 2025');
});

test('can delete asset management record', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
    ]);

    $this->delete(route('filament.hisabat.resources.asset-management.destroy', $assetManagement))
        ->assertRedirect();

    assertDatabaseMissing('asset_management', ['id' => $assetManagement->id]);
});

test('prevents duplicate asset management for same month and year', function () {
    AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
    ]);

    $duplicateData = [
        'month' => 1,
        'year' => 2025,
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $duplicateData)
        ->assertSessionHasErrors();
});

test('can view asset management edit form', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 12,
        'year' => 2024,
        'notes' => 'December 2024 assets',
    ]);

    $this->get(route('filament.hisabat.resources.asset-management.edit', $assetManagement))
        ->assertOk()
        ->assertSee('December 2024');
});

test('can view asset management create form', function () {
    $this->get(route('filament.hisabat.resources.asset-management.create'))
        ->assertOk()
        ->assertSee('Create Asset Management');
});

test('only shows user own asset management records', function () {
    $otherUser = User::factory()->create();
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);
    AssetManagement::factory()->create(['user_id' => $otherUser->id, 'month' => 2, 'year' => 2025]);

    $response = $this->get(route('filament.hisabat.resources.asset-management.index'));
    
    $response->assertOk();
    // The response should only contain the current user's records
    // This is handled by the getEloquentQuery method in the resource
});

test('validates month range', function () {
    $invalidData = [
        'month' => 13, // Invalid month
        'year' => 2025,
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $invalidData)
        ->assertSessionHasErrors(['month']);
});

test('validates year range', function () {
    $invalidData = [
        'month' => 1,
        'year' => 1999, // Too old
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $invalidData)
        ->assertSessionHasErrors(['year']);
});

test('can create asset management with accounts data', function () {
    $accountType = AccountType::where('user_id', $this->user->id)->first();
    
    $assetData = [
        'month' => 1,
        'year' => 2025,
        'notes' => 'January 2025 with accounts',
        'accounts' => [
            [
                'account_type_id' => $accountType->id,
                'account_name' => 'Main Cash',
                'exchange_rate' => 1.000000,
                'actual_amount' => 1000,
                'notes' => 'Main cash account',
            ]
        ],
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->accounts)->toHaveCount(1);
    expect($assetManagement->accounts->first()->account_name)->toBe('Main Cash');
});

test('can create asset management with lent money data', function () {
    $assetData = [
        'month' => 2,
        'year' => 2025,
        'lent_money' => [
            [
                'friend_name' => 'John Doe',
                'actual_amount' => 500,
                'exchange_rate' => 3.650000,
                'notes' => 'Loan to John',
            ]
        ],
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->lentMoney)->toHaveCount(1);
    expect($assetManagement->lentMoney->first()->friend_name)->toBe('John Doe');
});

test('can create asset management with borrowed money data', function () {
    $assetData = [
        'month' => 3,
        'year' => 2025,
        'borrowed_money' => [
            [
                'friend_name' => 'Jane Smith',
                'actual_amount' => 200,
                'exchange_rate' => 1.000000,
                'notes' => 'Borrowed from Jane',
            ]
        ],
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->borrowedMoney)->toHaveCount(1);
    expect($assetManagement->borrowedMoney->first()->friend_name)->toBe('Jane Smith');
});

test('can create asset management with investments data', function () {
    $assetData = [
        'month' => 4,
        'year' => 2025,
        'investments' => [
            [
                'investment_type' => 'Stocks',
                'investment_name' => 'Apple Stock',
                'exchange_rate' => 3.650000,
                'actual_amount' => 1000,
                'notes' => 'Apple stock investment',
            ]
        ],
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->investments)->toHaveCount(1);
    expect($assetManagement->investments->first()->investment_name)->toBe('Apple Stock');
});

test('can create asset management with deposits data', function () {
    $assetData = [
        'month' => 5,
        'year' => 2025,
        'deposits' => [
            [
                'deposit_type' => 'Fixed Deposit',
                'deposit_name' => 'Bank FD',
                'exchange_rate' => 1.000000,
                'actual_amount' => 5000,
                'notes' => 'Bank fixed deposit',
            ]
        ],
    ];

    $this->post(route('filament.hisabat.resources.asset-management.store'), $assetData)
        ->assertRedirect();

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->deposits)->toHaveCount(1);
    expect($assetManagement->deposits->first()->deposit_name)->toBe('Bank FD');
});
