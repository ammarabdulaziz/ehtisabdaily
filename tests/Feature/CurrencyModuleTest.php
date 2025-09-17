<?php

use App\Models\Currency;
use App\Models\User;
use App\Filament\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Resources\Currencies\Pages\ListCurrencies;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

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
    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'is_base' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    expect(Currency::where('code', 'USD')->first())
        ->not->toBeNull()
        ->and(Currency::where('code', 'USD')->first()->name)->toBe('US Dollar')
        ->and(Currency::where('code', 'USD')->first()->symbol)->toBe('$')
        ->and(Currency::where('code', 'USD')->first()->is_base)->toBeTrue();
});

test('can create currency without symbol', function () {
    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'EUR',
            'name' => 'Euro',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    expect(Currency::where('code', 'EUR')->first())
        ->not->toBeNull()
        ->and(Currency::where('code', 'EUR')->first()->name)->toBe('Euro')
        ->and(Currency::where('code', 'EUR')->first()->symbol)->toBeNull()
        ->and(Currency::where('code', 'EUR')->first()->is_base)->toBeFalse();
});

test('currency code is automatically uppercased', function () {
    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'qar',
            'name' => 'Qatari Riyal',
            'symbol' => 'ر.ق',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    expect(Currency::where('code', 'QAR')->first())
        ->not->toBeNull()
        ->and(Currency::where('code', 'QAR')->first()->name)->toBe('Qatari Riyal')
        ->and(Currency::where('code', 'QAR')->first()->symbol)->toBe('ر.ق');
});

test('validates required fields', function () {
    Livewire::test(CreateCurrency::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['code', 'name']);
});

test('validates currency code length', function () {
    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'USDD',
            'name' => 'US Dollar',
        ])
        ->call('create')
        ->assertHasFormErrors(['code']);
});

test('can edit existing currency', function () {
    $currency = Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => false,
    ]);

    Livewire::test(EditCurrency::class, ['record' => $currency->getRouteKey()])
        ->fillForm([
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => 'USD',
            'is_base' => true,
        ])
        ->call('save')
        ->assertNotified();

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

    Livewire::test(ListCurrencies::class)
        ->assertCanSeeTableRecords([$currency])
        ->assertSee('USD')
        ->assertSee('US Dollar')
        ->assertSee('$');
});

test('can search currencies by code', function () {
    $usd = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    $eur = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    Livewire::test(ListCurrencies::class)
        ->searchTable('USD')
        ->assertCanSeeTableRecords([$usd])
        ->assertCanNotSeeTableRecords([$eur]);
});

test('can search currencies by name', function () {
    $usd = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    $eur = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    Livewire::test(ListCurrencies::class)
        ->searchTable('Dollar')
        ->assertCanSeeTableRecords([$usd])
        ->assertCanNotSeeTableRecords([$eur]);
});

test('can filter by base currency', function () {
    $usd = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'is_base' => true]);
    $eur = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro', 'is_base' => false]);

    Livewire::test(ListCurrencies::class)
        ->filterTable('is_base', '1')
        ->assertCanSeeTableRecords([$usd])
        ->assertCanNotSeeTableRecords([$eur]);
});

test('can sort currencies by code', function () {
    $eur = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);
    $usd = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    Livewire::test(ListCurrencies::class)
        ->sortTable('code')
        ->assertCanSeeTableRecords([$eur, $usd]);
});

test('can delete currency using bulk action', function () {
    $currency1 = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
    $currency2 = Currency::factory()->create(['code' => 'EUR', 'name' => 'Euro']);

    Livewire::test(ListCurrencies::class)
        ->callTableBulkAction('delete', [$currency1])
        ->assertNotified();

    expect(Currency::find($currency1->id))->toBeNull();
    expect(Currency::find($currency2->id))->not->toBeNull();
});

test('prevents duplicate currency codes', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'USD',
            'name' => 'Another US Dollar',
        ])
        ->call('create')
        ->assertHasFormErrors(['code']);
});

test('can only have one base currency', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'is_base' => true]);

    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'code' => 'EUR',
            'name' => 'Euro',
            'is_base' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    // Check that only the new currency is base
    expect(Currency::where('is_base', true)->count())->toBe(1);
    expect(Currency::where('code', 'EUR')->first()->is_base)->toBeTrue();
    expect(Currency::where('code', 'USD')->first()->is_base)->toBeFalse();
});
