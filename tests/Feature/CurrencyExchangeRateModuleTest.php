<?php

use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Create some currencies for testing
    $this->usd = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_base' => false]);
    $this->eur = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'is_base' => false]);
    $this->qar = Currency::factory()->create(['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق', 'is_base' => true]);
});

test('guests cannot access currency exchange rates', function () {
    $this->post(route('logout'));
    
    $this->get(route('filament.hisabat.resources.currency-exchange-rates.index'))
        ->assertRedirect(route('filament.hisabat.auth.login'));
});

test('authenticated users can view currency exchange rates list', function () {
    $this->get(route('filament.hisabat.resources.currency-exchange-rates.index'))
        ->assertOk()
        ->assertSee('Exchange Rates');
});

test('can create a new currency exchange rate', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '3.64')
            ->type('date', '2025-01-15')
            ->type('source', 'Manual')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);
});

test('can create exchange rate without source', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->eur->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '4.12')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
        'date' => '2025-01-15',
        'source' => null,
    ]);
});

test('validates required fields', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->press('Create')
            ->waitForText('The from currency id field is required')
            ->waitForText('The to currency id field is required')
            ->waitForText('The rate field is required')
            ->waitForText('The date field is required');
    });
});

test('validates rate is numeric and positive', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '-1.5')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('The rate field must be at least 0');
    });
});

test('validates rate precision', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '3.1234567') // 7 decimal places
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully'); // Should work as it rounds to 6 decimal places
    });
});

test('can edit existing currency exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);

    $this->browse(function (Browser $browser) use ($exchangeRate) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.edit', $exchangeRate))
            ->waitForText('Edit Currency Exchange Rate')
            ->assertInputValue('rate', '3.64')
            ->assertInputValue('date', '2025-01-15')
            ->assertInputValue('source', 'Manual')
            ->type('rate', '3.65')
            ->type('source', 'API')
            ->press('Save')
            ->waitForText('Updated successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    $exchangeRate->refresh();
    expect($exchangeRate->rate)->toBe('3.65');
    expect($exchangeRate->source)->toBe('API');
});

test('can view currency exchange rate in table', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);

    $this->browse(function (Browser $browser) use ($exchangeRate) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->assertSee('US Dollar')
            ->assertSee('Qatari Riyal')
            ->assertSee('3.640000')
            ->assertSee('Jan 15, 2025')
            ->assertSee('Manual');
    });
});

test('can search exchange rates by currency name', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->type('table_search_input', 'US Dollar')
            ->waitForText('US Dollar')
            ->assertDontSee('Euro');
    });
});

test('can filter by from currency', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->click('button[aria-label="Filters"]')
            ->waitForText('From Currency')
            ->select('from_currency_id', $this->usd->id)
            ->press('Apply')
            ->waitForText('US Dollar')
            ->assertDontSee('Euro');
    });
});

test('can filter by to currency', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => '0.89',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->click('button[aria-label="Filters"]')
            ->waitForText('To Currency')
            ->select('to_currency_id', $this->qar->id)
            ->press('Apply')
            ->waitForText('Qatari Riyal')
            ->assertDontSee('Euro');
    });
});

test('can sort exchange rates by date', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
    ]);
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-20',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->click('th[data-sortable="true"]') // Click on Date column header
            ->waitForText('Jan 20, 2025')
            ->assertSeeIn('tbody tr:first-child', 'Jan 20, 2025')
            ->assertSeeIn('tbody tr:last-child', 'Jan 15, 2025');
    });
});

test('can sort exchange rates by rate', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-15',
    ]);
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.60',
        'date' => '2025-01-15',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->click('th[data-sortable="true"]') // Click on Rate column header
            ->waitForText('3.650000')
            ->assertSeeIn('tbody tr:first-child', '3.650000')
            ->assertSeeIn('tbody tr:last-child', '3.600000');
    });
});

test('can delete exchange rate using bulk action', function () {
    $exchangeRate1 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    $exchangeRate2 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
    ]);

    $this->browse(function (Browser $browser) use ($exchangeRate1, $exchangeRate2) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.index'))
            ->waitForText('Exchange Rates')
            ->click('input[type="checkbox"][value="' . $exchangeRate1->id . '"]')
            ->click('button[aria-label="Actions"]')
            ->waitForText('Delete')
            ->click('button:contains("Delete")')
            ->waitForText('Are you sure you want to delete the selected records?')
            ->press('Delete')
            ->waitForText('Deleted successfully');
    });

    assertDatabaseMissing('currency_exchange_rates', ['id' => $exchangeRate1->id]);
    assertDatabaseHas('currency_exchange_rates', ['id' => $exchangeRate2->id]);
});

test('can create exchange rate with same currencies but different dates', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '3.65')
            ->type('date', '2025-01-20')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    expect(CurrencyExchangeRate::where('from_currency_id', $this->usd->id)
        ->where('to_currency_id', $this->qar->id)
        ->count())->toBe(2);
});

test('can create exchange rate with different from and to currencies', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->eur->id)
            ->type('rate', '0.89')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => '0.89',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with future date', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '3.70')
            ->type('date', '2025-12-31')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.70',
        'date' => '2025-12-31',
    ]);
});

test('can create exchange rate with past date', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '3.60')
            ->type('date', '2024-01-01')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.60',
        'date' => '2024-01-01',
    ]);
});

test('can create exchange rate with very small rate', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '0.000001')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0.000001',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with very large rate', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '999999.999999')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '999999.999999',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with zero rate', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(route('filament.hisabat.resources.currency-exchange-rates.create'))
            ->waitForText('Create Currency Exchange Rate')
            ->select('from_currency_id', $this->usd->id)
            ->select('to_currency_id', $this->qar->id)
            ->type('rate', '0')
            ->type('date', '2025-01-15')
            ->press('Create')
            ->waitForText('Created successfully')
            ->assertPathIs('/admin/currency-exchange-rates');
    });

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0',
        'date' => '2025-01-15',
    ]);
});
