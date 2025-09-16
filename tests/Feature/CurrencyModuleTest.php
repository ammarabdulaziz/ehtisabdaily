<?php

use App\Models\Currency;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('guests cannot access currency management', function () {
    $this->post(route('logout'));
    
    $this->get(route('filament.hisabat.resources.currencies.index'))
        ->assertRedirect(route('filament.hisabat.auth.login'));
});

test('authenticated users can view currency list', function () {
    $this->get(route('filament.hisabat.resources.currencies.index'))
        ->assertOk()
        ->assertSee('Currencies');
});

test('can create a new currency', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'USD')
            ->type('name', 'US Dollar')
            ->type('symbol', '$')
            ->click('input[name="is_base"]')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currencies');
    });

    assertDatabaseHas('currencies', [
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => true,
    ]);
});

test('can create currency without symbol', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'EUR')
            ->type('name', 'Euro')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currencies');
    });

    assertDatabaseHas('currencies', [
        'code' => 'EUR',
        'name' => 'Euro',
        'symbol' => null,
        'is_base' => false,
    ]);
});

test('currency code is automatically uppercased', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'qar')
            ->type('name', 'Qatari Riyal')
            ->type('symbol', 'ر.ق')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currencies');
    });

    assertDatabaseHas('currencies', [
        'code' => 'QAR',
        'name' => 'Qatari Riyal',
        'symbol' => 'ر.ق',
    ]);
});

test('validates required fields', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->press('Create')
            ->waitForText('The code field is required')
            ->waitForText('The name field is required');
    });
});

test('validates currency code length', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'USDD')
            ->type('name', 'US Dollar')
            ->press('Create')
            ->waitForText('The code field must not be greater than 3 characters');
    });
});

test('can edit existing currency', function () {
    $currency = Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => false,
    ]);

    $this->browse(function (Browser $browser) use ($currency) {
        $browser->visit(route('filament.hisabat.resources.currencies.edit', $currency))
            ->waitForText('Edit Currency')
            ->assertInputValue('code', 'USD')
            ->assertInputValue('name', 'US Dollar')
            ->assertInputValue('symbol', '$')
            ->type('name', 'United States Dollar')
            ->type('symbol', 'USD')
            ->click('input[name="is_base"]')
            ->press('Save')
            ->waitForText('Updated successfully')
            ->assertPathIs('/admin/currencies');
    });

    $currency->refresh();
    expect($currency->name)->toBe('United States Dollar');
    expect($currency->symbol)->toBe('USD');
    expect($currency->is_base)->toBeTrue();
});

test('can view currency in table', function () {
    $currency = Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => true,
    ]);

    $this->browse(function (Browser $browser) use ($currency) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->assertSee('USD')
            ->assertSee('US Dollar')
            ->assertSee('$')
            ->assertSee('Yes'); // Base currency indicator
    });
});

test('can search currencies by code', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->type('table_search_input', 'USD')
            ->waitForText('USD')
            ->assertDontSee('EUR');
    });
});

test('can search currencies by name', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->type('table_search_input', 'Dollar')
            ->waitForText('USD')
            ->assertDontSee('EUR');
    });
});

test('can filter by base currency', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'is_base' => true]);
    Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro', 'is_base' => false]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->click('button[aria-label="Filters"]')
            ->waitForText('Base Currency')
            ->click('input[value="1"]') // Base currencies only
            ->press('Apply')
            ->waitForText('USD')
            ->assertDontSee('EUR');
    });
});

test('can sort currencies by code', function () {
    Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->click('th[data-sortable="true"]') // Click on Code column header
            ->waitForText('EUR')
            ->assertSeeIn('tbody tr:first-child', 'EUR')
            ->assertSeeIn('tbody tr:last-child', 'USD');
    });
});

test('can delete currency using bulk action', function () {
    $currency1 = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    $currency2 = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    $this->browse(function (Browser $browser) use ($currency1, $currency2) {
        $browser->visit(route('filament.hisabat.resources.currencies.index'))
            ->waitForText('Currencies')
            ->click('input[type="checkbox"][value="' . $currency1->id . '"]')
            ->click('button[aria-label="Actions"]')
            ->waitForText('Delete')
            ->click('button:contains("Delete")')
            ->waitForText('Are you sure you want to delete the selected records?')
            ->press('Delete')
            ->waitForText('Deleted successfully');
    });

    assertDatabaseMissing('currencies', ['id' => $currency1->id]);
    assertDatabaseHas('currencies', ['id' => $currency2->id]);
});

test('prevents duplicate currency codes', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'USD')
            ->type('name', 'Another US Dollar')
            ->press('Create')
            ->waitForText('The code has already been taken');
    });
});

test('can only have one base currency', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'is_base' => true]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currencies.create'))
            ->waitForText('Create Currency')
            ->type('code', 'EUR')
            ->type('name', 'Euro')
            ->click('input[name="is_base"]')
            ->press('Create')
            ->waitForText('Created successfully');
    });

    // Check that only the new currency is base
    expect(Currency::where('is_base', true)->count())->toBe(1);
    expect(Currency::where('code', 'EUR')->first()->is_base)->toBeTrue();
    expect(Currency::where('code', 'USD')->first()->is_base)->toBeFalse();
});
