<?php

use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use App\Models\User;
use App\Filament\Resources\CurrencyExchangeRates\Pages\CreateCurrencyExchangeRate;
use App\Filament\Resources\CurrencyExchangeRates\Pages\EditCurrencyExchangeRate;
use App\Filament\Resources\CurrencyExchangeRates\Pages\ListCurrencyExchangeRates;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Clean up any existing exchange rates to avoid unique constraint violations
    CurrencyExchangeRate::truncate();
    
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
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '3.64',
            'date' => '2025-01-15',
            'source' => 'Manual',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 3.64,
        'source' => 'Manual',
    ]);
});

test('can create exchange rate without source', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->eur->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '4.12',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 4.12,
        'source' => null,
    ]);
});

test('validates required fields', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['from_currency_id', 'to_currency_id', 'rate', 'date']);
});

test('validates rate is numeric and positive', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '-1.5',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertHasFormErrors(['rate']);
});

test('validates rate precision', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '3.1234567', // 7 decimal places
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified(); // Should work as it rounds to 6 decimal places
});

test('can edit existing currency exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
        'source' => 'Manual',
    ]);

    Livewire::test(EditCurrencyExchangeRate::class, ['record' => $exchangeRate->getRouteKey()])
        ->fillForm([
            'rate' => '3.65',
            'source' => 'API',
        ])
        ->call('save')
        ->assertNotified();

    $exchangeRate->refresh();
    expect($exchangeRate->rate)->toBe('3.650000');
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

    Livewire::test(ListCurrencyExchangeRates::class)
        ->assertCanSeeTableRecords([$exchangeRate])
        ->assertSee('US Dollar')
        ->assertSee('Qatari Riyal')
        ->assertSee('3.640000')
        ->assertSee('Manual');
});

test('can search exchange rates by currency name', function () {
    $usdRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    $eurRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
    ]);

    Livewire::test(ListCurrencyExchangeRates::class)
        ->searchTable('US Dollar')
        ->assertCanSeeTableRecords([$usdRate])
        ->assertCanNotSeeTableRecords([$eurRate]);
});

test('can filter by from currency', function () {
    $usdRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    $eurRate = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->eur->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '4.12',
    ]);

    Livewire::test(ListCurrencyExchangeRates::class)
        ->filterTable('from_currency_id', $this->usd->id)
        ->assertCanSeeTableRecords([$usdRate])
        ->assertCanNotSeeTableRecords([$eurRate]);
});

test('can filter by to currency', function () {
    $usdToQar = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
    ]);
    $usdToEur = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => '0.89',
    ]);

    Livewire::test(ListCurrencyExchangeRates::class)
        ->filterTable('to_currency_id', $this->qar->id)
        ->assertCanSeeTableRecords([$usdToQar])
        ->assertCanNotSeeTableRecords([$usdToEur]);
});

test('can sort exchange rates by date', function () {
    $rate1 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.64',
        'date' => '2025-01-15',
    ]);
    $rate2 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-20',
    ]);

    Livewire::test(ListCurrencyExchangeRates::class)
        ->sortTable('date')
        ->assertCanSeeTableRecords([$rate1, $rate2], inOrder: true);
});

test('can sort exchange rates by rate', function () {
    $rate1 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.60',
        'date' => '2025-01-15',
    ]);
    $rate2 = CurrencyExchangeRate::factory()->create([
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => '3.65',
        'date' => '2025-01-16',  // Different date to avoid unique constraint
    ]);

    Livewire::test(ListCurrencyExchangeRates::class)
        ->sortTable('rate')
        ->assertCanSeeTableRecords([$rate1, $rate2], inOrder: true);
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

    Livewire::test(ListCurrencyExchangeRates::class)
        ->callTableBulkAction('delete', [$exchangeRate1])
        ->assertNotified();

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

    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '3.65',
            'date' => '2025-01-20',
        ])
        ->call('create')
        ->assertNotified();

    expect(CurrencyExchangeRate::where('from_currency_id', $this->usd->id)
        ->where('to_currency_id', $this->qar->id)
        ->count())->toBe(2);
});

test('can create exchange rate with different from and to currencies', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->eur->id,
            'rate' => '0.89',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->eur->id,
        'rate' => 0.89,
    ]);
});

test('can create exchange rate with future date', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '3.70',
            'date' => '2025-12-31',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 3.70,
    ]);
});

test('can create exchange rate with past date', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '3.60',
            'date' => '2024-01-01',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 3.60,
    ]);
});

test('can create exchange rate with very small rate', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '0.000001',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 0.000001,
    ]);
});

test('can create exchange rate with very large rate', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '999999.999999',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 999999.999999,
    ]);
});

test('can create exchange rate with zero rate', function () {
    Livewire::test(CreateCurrencyExchangeRate::class)
        ->fillForm([
            'from_currency_id' => $this->usd->id,
            'to_currency_id' => $this->qar->id,
            'rate' => '0',
            'date' => '2025-01-15',
        ])
        ->call('create')
        ->assertNotified();

    assertDatabaseHas('currency_exchange_rates', [
        'from_currency_id' => $this->usd->id,
        'to_currency_id' => $this->qar->id,
        'rate' => 0,
    ]);
});