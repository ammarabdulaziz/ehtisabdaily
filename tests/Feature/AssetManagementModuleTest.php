<?php

use App\Models\AssetManagement;
use App\Models\Currency;
use App\Models\User;
use App\Models\AccountType;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Create some currencies for testing
    Currency::factory()->create(['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق', 'is_base' => true]);
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_base' => false]);
    
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
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '12')
            ->select('year', '2024')
            ->type('notes', 'December 2024 assets')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    assertDatabaseHas('asset_management', [
        'user_id' => $this->user->id,
        'month' => 12,
        'year' => 2024,
        'notes' => 'December 2024 assets',
    ]);
});

test('can create asset management with accounts', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '1')
            ->select('year', '2025')
            ->type('notes', 'January 2025 assets')
            
            // Add an account
            ->click('button:contains("Add Account")')
            ->waitForText('Account Type')
            ->select('accounts.0.account_type_id', '1') // Cash-in-Hand
            ->type('accounts.0.account_name', 'Main Cash')
            ->select('accounts.0.currency', 'QAR')
            ->type('accounts.0.amount', '1000')
            ->type('accounts.0.notes', 'Main cash account')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->month)->toBe(1);
    expect($assetManagement->year)->toBe(2025);
    expect($assetManagement->accounts)->toHaveCount(1);
    expect($assetManagement->accounts->first()->account_name)->toBe('Main Cash');
});

test('can create asset management with lent money', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '2')
            ->select('year', '2025')
            
            // Add lent money
            ->click('button:contains("Add Lent Money")')
            ->waitForText('Friend/Person Name')
            ->type('lent_money.0.friend_name', 'John Doe')
            ->type('lent_money.0.amount', '500')
            ->select('lent_money.0.currency', 'USD')
            ->type('lent_money.0.notes', 'Loan to John')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->lentMoney)->toHaveCount(1);
    expect($assetManagement->lentMoney->first()->friend_name)->toBe('John Doe');
    expect($assetManagement->lentMoney->first()->amount)->toBe(500.0);
});

test('can create asset management with borrowed money', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '3')
            ->select('year', '2025')
            
            // Add borrowed money
            ->click('button:contains("Add Borrowed Money")')
            ->waitForText('Friend/Person Name')
            ->type('borrowed_money.0.friend_name', 'Jane Smith')
            ->type('borrowed_money.0.amount', '200')
            ->select('borrowed_money.0.currency', 'QAR')
            ->type('borrowed_money.0.notes', 'Borrowed from Jane')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->borrowedMoney)->toHaveCount(1);
    expect($assetManagement->borrowedMoney->first()->friend_name)->toBe('Jane Smith');
    expect($assetManagement->borrowedMoney->first()->amount)->toBe(200.0);
});

test('can create asset management with investments', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '4')
            ->select('year', '2025')
            
            // Add investment
            ->click('button:contains("Add Investment")')
            ->waitForText('Investment Type')
            ->type('investments.0.investment_type', 'Stocks')
            ->type('investments.0.investment_name', 'Apple Stock')
            ->select('investments.0.currency', 'USD')
            ->type('investments.0.amount', '1000')
            ->type('investments.0.notes', 'Apple stock investment')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->investments)->toHaveCount(1);
    expect($assetManagement->investments->first()->investment_name)->toBe('Apple Stock');
    expect($assetManagement->investments->first()->amount)->toBe(1000.0);
});

test('can create asset management with deposits', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '5')
            ->select('year', '2025')
            
            // Add deposit
            ->click('button:contains("Add Deposit")')
            ->waitForText('Deposit Type')
            ->type('deposits.0.deposit_type', 'Fixed Deposit')
            ->type('deposits.0.deposit_name', 'Bank FD')
            ->select('deposits.0.currency', 'QAR')
            ->type('deposits.0.amount', '5000')
            ->type('deposits.0.notes', 'Bank fixed deposit')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement = AssetManagement::where('user_id', $this->user->id)->first();
    expect($assetManagement->deposits)->toHaveCount(1);
    expect($assetManagement->deposits->first()->deposit_name)->toBe('Bank FD');
    expect($assetManagement->deposits->first()->amount)->toBe(5000.0);
});

test('can create new account type inline', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '6')
            ->select('year', '2025')
            
            // Add account with new account type
            ->click('button:contains("Add Account")')
            ->waitForText('Account Type')
            ->click('button:contains("Create new")')
            ->waitForText('Create Account Type')
            ->type('name', 'Savings Account')
            ->type('description', 'Personal savings account')
            ->press('Create')
            ->waitForText('Created successfully')
            ->type('accounts.0.account_name', 'My Savings')
            ->select('accounts.0.currency', 'QAR')
            ->type('accounts.0.amount', '2000')
            
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/asset-management');
    });

    // Check that the new account type was created
    assertDatabaseHas('account_types', [
        'user_id' => $this->user->id,
        'name' => 'Savings Account',
        'description' => 'Personal savings account',
    ]);
});

test('validates required fields', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->press('Create')
            ->waitForText('The month field is required')
            ->waitForText('The year field is required');
    });
});

test('can edit existing asset management record', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
        'notes' => 'Original notes',
    ]);

    $this->browse(function (Browser $browser) use ($assetManagement) {
        $browser->visit(route('filament.hisabat.resources.asset-management.edit', $assetManagement))
            ->waitForText('Edit Asset Management')
            ->assertInputValue('month', '1')
            ->assertInputValue('year', '2025')
            ->assertInputValue('notes', 'Original notes')
            ->type('notes', 'Updated notes for January 2025')
            ->press('Save')
            ->waitForText('Updated successfully')
            ->assertPathIs('/admin/asset-management');
    });

    $assetManagement->refresh();
    expect($assetManagement->notes)->toBe('Updated notes for January 2025');
});

test('can view asset management in table', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 12,
        'year' => 2024,
        'notes' => 'December 2024 assets',
    ]);

    $this->browse(function (Browser $browser) use ($assetManagement) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->assertSee('December 2024')
            ->assertSee('0.00') // Grand total
            ->assertSee('0.00'); // Savings
    });
});

test('can filter by year', function () {
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2024]);
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->click('button[aria-label="Filters"]')
            ->waitForText('Year')
            ->select('year', '2024')
            ->press('Apply')
            ->waitForText('January 2024')
            ->assertDontSee('January 2025');
    });
});

test('can filter by month', function () {
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 12, 'year' => 2025]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->click('button[aria-label="Filters"]')
            ->waitForText('Month')
            ->select('month', '1')
            ->press('Apply')
            ->waitForText('January 2025')
            ->assertDontSee('December 2025');
    });
});

test('can search by period', function () {
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 12, 'year' => 2025]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->type('table_search_input', 'January')
            ->waitForText('January 2025')
            ->assertDontSee('December 2025');
    });
});

test('can delete asset management using bulk action', function () {
    $assetManagement1 = AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);
    $assetManagement2 = AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 2, 'year' => 2025]);

    $this->browse(function (Browser $browser) use ($assetManagement1, $assetManagement2) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->click('input[type="checkbox"][value="' . $assetManagement1->id . '"]')
            ->click('button[aria-label="Actions"]')
            ->waitForText('Delete')
            ->click('button:contains("Delete")')
            ->waitForText('Are you sure you want to delete the selected records?')
            ->press('Delete')
            ->waitForText('Deleted successfully');
    });

    assertDatabaseMissing('asset_management', ['id' => $assetManagement1->id]);
    assertDatabaseHas('asset_management', ['id' => $assetManagement2->id]);
});

test('prevents duplicate asset management for same month and year', function () {
    AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.create'))
            ->waitForText('Create Asset Management')
            ->select('month', '1')
            ->select('year', '2025')
            ->press('Create')
            ->waitForText('The combination of month and year has already been taken');
    });
});

test('shows correct totals in table', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
    ]);

    // Add some accounts
    $assetManagement->accounts()->create([
        'account_type_id' => 1, // Cash-in-Hand
        'account_name' => 'Main Cash',
        'currency' => 'QAR',
        'amount' => 1000,
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->assertSee('1,000.00') // Total accounts
            ->assertSee('1,000.00'); // Grand total
    });
});

test('can view asset management details', function () {
    $assetManagement = AssetManagement::factory()->create([
        'user_id' => $this->user->id,
        'month' => 1,
        'year' => 2025,
        'notes' => 'January 2025 assets',
    ]);

    $this->browse(function (Browser $browser) use ($assetManagement) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->click('button[aria-label="View"]')
            ->waitForText('View Asset Management')
            ->assertSee('January 2025')
            ->assertSee('January 2025 assets');
    });
});

test('only shows user own asset management records', function () {
    $otherUser = User::factory()->create();
    AssetManagement::factory()->create(['user_id' => $this->user->id, 'month' => 1, 'year' => 2025]);
    AssetManagement::factory()->create(['user_id' => $otherUser->id, 'month' => 2, 'year' => 2025]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.asset-management.index'))
            ->waitForText('Asset Management')
            ->assertSee('January 2025')
            ->assertDontSee('February 2025');
    });
});
