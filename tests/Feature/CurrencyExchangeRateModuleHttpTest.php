<?php

use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use App\Models\User;
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
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);
});

test('can create exchange rate without source', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
        'date' => '2025-01-15',
        'source' => null,
    ]);
});

test('validates required fields', function () {
    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), [])
        ->assertSessionHasErrors(['from_currency_id', 'to_currency_id', 'rate', 'date']);
});

test('validates rate is numeric and positive', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '-1.5', // Negative rate
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertSessionHasErrors(['rate']);
});

test('can update existing currency exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);

    $updateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-15',
        'source' => 'API',
    ];

    $this->put(route('filament.hisabat.resources.currency-exchange-rates.update', $exchangeRate), $updateData)
        ->assertRedirect();

    $exchangeRate->refresh();
    expect($exchangeRate->rate)->toBe('3.65');
    expect($exchangeRate->source)->toBe('API');
});

test('can delete exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);

    $this->delete(route('filament.hisabat.resources.currency-exchange-rates.destroy', $exchangeRate))
        ->assertRedirect();

    assertDatabaseMissing('currency_exchange_rates', ['id' => $exchangeRate->id]);
});

test('can view exchange rate edit form', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);

    $this->get(route('filament.hisabat.resources.currency-exchange-rates.edit', $exchangeRate))
        ->assertOk()
        ->assertSee('3.64')
        ->assertSee('Manual');
});

test('can view exchange rate create form', function () {
    $this->get(route('filament.hisabat.resources.currency-exchange-rates.create'))
        ->assertOk()
        ->assertSee('Create Currency Exchange Rate');
});

test('can create exchange rate with same currencies but different dates', function () {
    CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
    ]);

    $newRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-20',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $newRateData)
        ->assertRedirect();

    expect(CurrencyExchangeRate::where('from_currency_id', $this->usd->id)
        ->where('to_currency_id', $this->qar->id)
        ->count())->toBe(2);
});

test('can create exchange rate with different from and to currencies', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => '0.89',
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => '0.89',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with future date', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.70',
        'date' => '2025-12-31',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.70',
        'date' => '2025-12-31',
    ]);
});

test('can create exchange rate with past date', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.60',
        'date' => '2024-01-01',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.60',
        'date' => '2024-01-01',
    ]);
});

test('can create exchange rate with very small rate', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0.000001',
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0.000001',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with very large rate', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '999999.999999',
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '999999.999999',
        'date' => '2025-01-15',
    ]);
});

test('can create exchange rate with zero rate', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0',
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '0',
        'date' => '2025-01-15',
    ]);
});

test('validates rate precision', function () {
    $exchangeRateData = [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.1234567', // 7 decimal places - should be rounded to 6
        'date' => '2025-01-15',
    ];

    $this->post(route('filament.hisabat.resources.currency-exchange-rates.store'), $exchangeRateData)
        ->assertRedirect();

    // Check that the rate was stored with 6 decimal places
    $exchangeRate = CurrencyExchangeRate::where('from_currency_id', $this->usd->id)
        ->where('to_currency_id', $this->qar->id)
        ->first();
    
    expect($exchangeRate->rate)->toBe('3.123457'); // Rounded to 6 decimal places
});
